<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Checkout extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Keranjang_model');
        $this->load->model('Pembeli_model');
        $this->load->model('Transaksi_model');
        $this->load->library('rajaongkir');
        $this->load->model('Checkout_model');
        $this->load->library('form_validation');
        $this->load->library('session');

        // Check if user is logged in
        if (!$this->session->userdata('pembeli_id')) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        // Check session
        if (!$this->session->userdata('logged_in_pembeli')) {
            redirect('login');
            return;
        }

        $pembeli_id = $this->session->userdata('pembeli_id');

        // Get checkout session
        $checkout_session = $this->session->userdata('checkout_session');

        if (!$checkout_session || $checkout_session['pembeli_id'] != $pembeli_id) {
            // Tidak ada session checkout, redirect ke keranjang
            $this->session->set_flashdata('error', 'Sesi checkout tidak ditemukan. Silakan pilih item dari keranjang.');
            redirect('keranjang');
            return;
        }

        // Check if session expired
        if (time() > $checkout_session['expires_at']) {
            $this->session->unset_userdata('checkout_session');
            $this->session->set_flashdata('error', 'Sesi checkout telah expired. Silakan ulangi proses checkout.');
            redirect('keranjang');
            return;
        }

        // Get checkout items from session
        $checkout_items = $checkout_session['items'];

        // Group items by store
        $keranjang_grouped = [];
       foreach ($checkout_items as $item) {
    $keranjang_grouped[$item['nama_toko']][] = $item;
}

        // Get other necessary data
        $data = [
            'title' => 'Checkout',
            'keranjang_grouped' => $keranjang_grouped,
            'pembeli' => $this->Pembeli_model->get_pembeli($pembeli_id),
            'provinsi_list' => $this->get_provinces(), // Method untuk get provinces
            'checkout_session' => $checkout_session
        ];

        // echo "<pre>";print_r($data);echo "</pre>"; die();

        $this->load->view('homepage/header');
        $this->load->view('homepage/menu');
        $this->load->view('checkout/index', $data);
        $this->load->view('homepage/footer');
    }

    public function process() {
    header('Content-Type: application/json');

    try {
        // Check session
        if (!$this->session->userdata('logged_in_pembeli')) {
            throw new Exception('Silakan login terlebih dahulu', 401);
        }

        $pembeli_id = $this->session->userdata('pembeli_id');
        $raw_item_ids = $this->input->post('item_ids');

        // Debug logging
        log_message('debug', 'Checkout Process Initiated');
        log_message('debug', 'Pembeli ID: ' . $pembeli_id);
        log_message('debug', 'Raw item_ids: ' . print_r($raw_item_ids, true));

        // Process item_ids
        $item_ids = [];
        if (!empty($raw_item_ids)) {
            if (is_string($raw_item_ids)) {
                $decoded = json_decode($raw_item_ids, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $item_ids = $decoded;
                } else {
                    $item_ids = explode(',', $raw_item_ids);
                }
            } elseif (is_array($raw_item_ids)) {
                $item_ids = $raw_item_ids;
            }
        }

        // Validate item_ids
        $item_ids = array_filter(array_map('intval', (array)$item_ids), function ($id) {
            return $id > 0;
        });

        if (empty($item_ids)) {
            throw new Exception('Tidak ada item yang dipilih atau format tidak valid', 400);
        }

        log_message('debug', 'Processed item_ids: ' . print_r($item_ids, true));

        // Validate items
        $valid_items = $this->Checkout_model->validate_checkout_items($pembeli_id, $item_ids);
        if (empty($valid_items)) {
            throw new Exception('Item tidak valid atau stok tidak mencukupi', 400);
        }

        // Konversi ke array jika ada yang object
        $valid_items_array = array_map(function($item) {
            return is_object($item) ? (array)$item : $item;
        }, $valid_items);

        log_message('debug', 'Valid items: ' . print_r($valid_items_array, true));

        // Cek apakah item berasal dari multiple toko
        $toko_ids = array_unique(array_column($valid_items_array, 'id_toko'));
        if (count($toko_ids) > 1) {
            throw new Exception('Checkout hanya boleh untuk satu toko saja. Silakan pilih item dari satu toko.', 400);
        }

        // Create checkout session
        $session_created = $this->Checkout_model->create_checkout_session($pembeli_id, $valid_items_array);
        if (!$session_created){
            throw new Exception('Gagal membuat sesi checkout', 500);
        }

        // Response sukses
        echo json_encode([
            'status' => 'success',
            'message' => 'Checkout berhasil diproses',
            'redirect' => site_url('checkout')
        ]);

    } catch (Exception $e) {
        $status_code = $e->getCode() ?: 500;
        http_response_code($status_code);

        log_message('error', 'Checkout Error: ' . $e->getMessage());
        log_message('debug', 'Error Trace: ' . print_r($e->getTrace(), true));

        $response = [
            'status' => 'error',
            'message' => $e->getMessage(),
            'error_code' => $status_code
        ];

        if (ENVIRONMENT === 'development') {
            $response['debug'] = [
                'trace' => $e->getTrace(),
                'post_data' => $this->input->post(),
                'session' => $this->session->userdata()
            ];
        }

        echo json_encode($response);
    }
}

    /**
     * Get cities by province ID (AJAX)
     */
    public function get_kota($province_id)
    {
        $cities_data = $this->rajaongkir->get_cities($province_id);

        if ($cities_data['status']) {
            echo json_encode($cities_data['data']['results']);
        } else {
            echo json_encode(array());
        }
    }

    /**
     * Get shipping services by city and courier (AJAX)
     */
    public function get_layanan($origin_city_id, $destination_city_id, $weight, $courier)
{
    // Validasi parameter
    if (empty($origin_city_id) || empty($destination_city_id) || empty($weight) || empty($courier)) {
        echo json_encode(['error' => 'Parameter tidak lengkap']);
        return;
    }

    // Pastikan berat minimal 1000 gram
    $weight = max($weight, 1000);

    // Dapatkan data ongkir dari RajaOngkir
    $cost_data = $this->rajaongkir->get_cost($origin_city_id, $destination_city_id, $weight, $courier);

    // Format response
    if ($cost_data['status'] && !empty($cost_data['data']['results'][0]['costs'])) {
        $services = [];
        foreach ($cost_data['data']['results'][0]['costs'] as $service) {
            $services[] = [
                'service' => $service['service'],
                'description' => $service['description'],
                'cost' => $service['cost'][0]['value'],
                'etd' => str_replace(' HARI', '', $service['cost'][0]['etd']),
                'note' => $service['cost'][0]['note'] ?? ''
            ];
        }
        echo json_encode($services);
    } else {
        // Log error untuk debugging
        $error_msg = $cost_data['message'] ?? 'Tidak ada layanan tersedia';
        log_message('error', 'RajaOngkir Error - Origin: ' . $origin_city_id . 
                  ', Dest: ' . $destination_city_id . 
                  ', Weight: ' . $weight . 
                  ', Courier: ' . $courier . 
                  ', Error: ' . $error_msg);
        
        echo json_encode(['error' => $error_msg]);
    }
}

    /**
     * Process checkout
     */
    public function proses()
    {
        if (!$this->session->userdata('logged_in_pembeli')) {
            redirect('login');
            return;
        }

        $pembeli_id = $this->session->userdata('pembeli_id');

        // Get checkout session
        $checkout_session = $this->session->userdata('checkout_session');

        if (!$checkout_session || $checkout_session['pembeli_id'] != $pembeli_id) {
            $this->session->set_flashdata('error', 'Sesi checkout tidak valid.');
            redirect('keranjang');
            return;
        }

        // Process the final checkout with shipping data
        $shipping_data = [
            'nama_penerima' => $this->input->post('nama_penerima'),
            'no_hp' => $this->input->post('no_hp'),
            'alamat' => $this->input->post('alamat'),
            'provinsi' => $this->input->post('provinsi'),
            'kota' => $this->input->post('kota'),
            'kode_pos' => $this->input->post('kode_pos'),
            'kurir' => $this->input->post('kurir'),
            'layanan' => $this->input->post('layanan'),
            'ongkir' => $this->input->post('ongkir')
        ];

        // Now process the actual checkout
        $checkout_result = $this->Checkout_model->process_checkout($pembeli_id, $checkout_session['items']);

        if (is_array($checkout_result) && $checkout_result['status'] === true) {
            // Finalize checkout
            $finalized = $this->Checkout_model->finalize_checkout($pembeli_id, $checkout_result['order_ids'], $shipping_data);

            if ($finalized) {
                $this->session->set_flashdata('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
                redirect('pesanan');
            } else {
                $this->session->set_flashdata('error', 'Terjadi kesalahan dalam finalisasi pesanan.');
                redirect('checkout');
            }
        } else {
            $error_msg = is_array($checkout_result) ? $checkout_result['message'] : 'Proses checkout gagal';
            $this->session->set_flashdata('error', $error_msg);
            redirect('checkout');
        }
    }

    private function get_provinces()
    {
        // Load RajaOngkir library
        $this->load->library('rajaongkir');

        $provinces = $this->rajaongkir->get_provinces();

        if ($provinces['status']) {
            return $provinces['data']['results'];
        }

        return [];
    }

    /**
     * Process the order
     */
    private function process_order()
    {
        $pembeli_id = $this->session->userdata('pembeli_id');
        $keranjang_grouped = $this->get_keranjang_grouped();

        // Get POST data
        $nama_penerima = $this->input->post('nama_penerima');
        $no_hp = $this->input->post('no_hp');
        $alamat = $this->input->post('alamat');
        $provinsi = $this->input->post('provinsi');
        $kota = $this->input->post('kota');
        $kode_pos = $this->input->post('kode_pos');
        $kurir_data = $this->input->post('kurir');
        $layanan_data = $this->input->post('layanan');
        $ongkir_data = $this->input->post('ongkir');

        // Get province and city names
        $province_data = $this->rajaongkir->get_province($provinsi);
        $city_data = $this->rajaongkir->get_city($kota);

        $nama_provinsi = $province_data['status'] ? $province_data['data']['results']['province'] : '';
        $nama_kota = $city_data['status'] ? $city_data['data']['results']['city_name'] : '';

        $this->db->trans_start();

        try {
            // Create transactions for each store
            foreach ($keranjang_grouped as $nama_toko => $items) {
                // Calculate subtotal for this store
                $subtotal = 0;
                foreach ($items as $item) {
                    $subtotal += $item->harga * $item->jumlah;
                }

                $ongkir = $ongkir_data[$nama_toko];
                $total = $subtotal + $ongkir;

                // Generate invoice number
                $invoice = $this->generate_invoice_number();

                // Insert transaction
                $transaksi_data = array(
                    'invoice' => $invoice,
                    'pembeli_id' => $pembeli_id,
                    'toko_nama' => $nama_toko,
                    'tanggal' => date('Y-m-d H:i:s'),
                    'subtotal' => $subtotal,
                    'ongkir' => $ongkir,
                    'total' => $total,
                    'status' => 'pending',

                    // Shipping address
                    'nama_penerima' => $nama_penerima,
                    'no_hp' => $no_hp,
                    'alamat' => $alamat,
                    'provinsi' => $nama_provinsi,
                    'kota' => $nama_kota,
                    'kode_pos' => $kode_pos,

                    // Shipping method
                    'kurir' => $kurir_data[$nama_toko],
                    'layanan' => $layanan_data[$nama_toko]
                );

                $transaksi_id = $this->Transaksi_model->insert($transaksi_data);

                // Insert transaction details
                foreach ($items as $item) {
                    $detail_data = array(
                        'transaksi_id' => $transaksi_id,
                        'produk_id' => $item->produk_id,
                        'nama_produk' => $item->nama_produk,
                        'harga' => $item->harga,
                        'jumlah' => $item->jumlah,
                        'subtotal' => $item->harga * $item->jumlah
                    );

                    $this->Transaksi_model->insert_detail($detail_data);

                    // Remove from cart
                    $this->Keranjang_model->delete($item->keranjang_id);
                }
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menyimpan transaksi');
            }

            $this->session->set_flashdata('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');
            redirect('transaksi');

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
            redirect('checkout');
        }
    }

    /**
     * Group cart items by store
     */
    private function group_by_store($keranjang)
    {
        $grouped = array();

        foreach ($keranjang as $item) {
            $store_name = $item->nama_toko ?? 'Toko Default';

            if (!isset($grouped[$store_name])) {
                $grouped[$store_name] = array();
            }

            $grouped[$store_name][] = $item;
        }

        return $grouped;
    }

    /**
     * Get keranjang grouped by store
     */
    private function get_keranjang_grouped()
    {
        $pembeli_id = $this->session->userdata('pembeli_id');
        $keranjang = $this->Keranjang_model->get_keranjang_by_pembeli($pembeli_id);
        return $this->group_by_store($keranjang);
    }

    /**
     * Generate unique invoice number
     */
    private function generate_invoice_number()
    {
        $prefix = 'INV';
        $date = date('Ymd');
        $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

        return $prefix . $date . $random;
    }

    /**
     * Get shipping cost for specific store (AJAX)
     */
 public function get_shipping_cost()
{
    header('Content-Type: application/json');
    
    try {
        $destination_city_id = $this->input->post('destination_city_id', true);
        $courier = $this->input->post('courier', true);
        $store_name = $this->input->post('store_name', true);
        $origin_city_id = $this->input->post('origin_city_id', true) ?? 501; // Default jika tidak ada

        // Validasi input
        if (empty($destination_city_id)) {
            throw new Exception('Kota tujuan tidak valid');
        }
        if (empty($courier)) {
            throw new Exception('Kurir tidak valid');
        }

        // Hitung berat dari keranjang
        $pembeli_id = $this->session->userdata('pembeli_id');
        $keranjang = $this->Keranjang_model->get_keranjang_by_pembeli($pembeli_id);
        $total_weight = 0;

        foreach ($keranjang as $item) {
            if ($item->nama_toko == $store_name) {
                $weight = $item->berat ?? 500; // Default 500 gram jika berat tidak ada
                $total_weight += $weight * $item->jumlah;
            }
        }

        // Minimum weight 1000 gram
        $total_weight = max($total_weight, 1000);

        // Dapatkan data ongkir
        $cost_data = $this->rajaongkir->get_cost($origin_city_id, $destination_city_id, $total_weight, $courier);

        if ($cost_data['status'] && !empty($cost_data['data']['results'][0]['costs'])) {
            $response = [
                'status' => true,
                'data' => $cost_data['data']['results'][0]['costs'],
                'debug' => [
                    'origin' => $origin_city_id,
                    'destination' => $destination_city_id,
                    'weight' => $total_weight,
                    'courier' => $courier
                ]
            ];
        } else {
            $response = [
                'status' => false,
                'message' => $cost_data['message'] ?? 'Tidak ada layanan tersedia',
                'debug' => $cost_data
            ];
        }

        echo json_encode($response);

    } catch (Exception $e) {
        echo json_encode([
            'status' => false,
            'message' => $e->getMessage()
        ]);
    }
}

    /**
     * Calculate total weight for all items
     */
    private function calculate_total_weight($items)
    {
        $total_weight = 0;

        foreach ($items as $item) {
            // Default weight 500 gram if not specified
            $weight = isset($item->berat) ? $item->berat : 500;
            $total_weight += $weight * $item->jumlah;
        }

        // Minimum weight 1000 gram
        return max($total_weight, 1000);
    }
}