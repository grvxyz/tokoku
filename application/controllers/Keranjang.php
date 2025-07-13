<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Keranjang extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Keranjang_model', 'Produk_model', 'Pembeli_model', 'Pesanan_model']);
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
    }

    public function index()
    {
        $id_pembeli = $this->session->userdata('id_pembeli');
        if (!$id_pembeli) redirect('home/login');

        $data['keranjang'] = $this->Keranjang_model->getKeranjang($id_pembeli);
        $data['user'] = $this->Pembeli_model->get_by_id($id_pembeli); // pastikan fungsi ini ada di model
        $data['total'] = 0;
        $data['berat_total'] = 0;

        foreach ($data['keranjang'] as $item) {
            $data['total'] += $item->qty * $item->harga;
            $data['berat_total'] += $item->berat * $item->qty;
        }

        $data['kurir'] = $this->session->userdata('kurir');
        $data['biaya_kirim'] = $this->session->userdata('biaya_kirim') ?? 32000;
        $data['asuransi'] = 800;
        $data['total_tagihan'] = $data['total'] + $data['biaya_kirim'] + $data['asuransi'];

        $this->load->view('homepage/header');
        $this->load->view('homepage/menu');
        $this->load->view('admin/pembeli/keranjang', $data);
        $this->load->view('homepage/footer');
    }

    public function pilih_kurir()
    {
        $kurir = $this->input->post('kurir', TRUE);
        if (!$kurir) {
            $this->session->set_flashdata('error', 'Silakan pilih metode pengiriman.');
            redirect('keranjang');
        }

      switch ($kurir) {
    case 'ekonomi':
        $biaya_kirim = 32000;
        break;
    case 'standard':
        $biaya_kirim = 42400;
        break;
    case 'reguler':
        $biaya_kirim = 38400;
        break;
    default:
        $biaya_kirim = 32000;
        break;
}
        $berat_total = $this->input->post('berat_total', TRUE);
        if (!$berat_total) {
            $this->session->set_flashdata('error', 'Berat total tidak ditemukan.');
            redirect('keranjang');
        }

        $this->session->set_userdata('kurir', $kurir);
        $this->session->set_userdata('biaya_kirim', $biaya_kirim);

        redirect('keranjang');
    }

    public function get_token()
    {
        require_once APPPATH . 'libraries/Midtrans/Midtrans.php';

        \Midtrans\Config::$serverKey = 'SB-Mid-server-S1U1lqPi4DV1MUoLoAF8kMPN';
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $metode = $this->input->post('metode_pembayaran', TRUE);
        $id_pembeli = $this->session->userdata('id_pembeli');
        $kurir = $this->session->userdata('kurir');
        $biaya_kirim = $this->session->userdata('biaya_kirim') ?? 32000;
        $asuransi = 800;

        if (!$id_pembeli || !$kurir || !$metode) {
            echo json_encode(['status' => 'error', 'message' => 'Kurir dan metode pembayaran wajib dipilih.']);
            return;
        }

        $keranjang = $this->Keranjang_model->getKeranjang($id_pembeli);
        if (empty($keranjang)) {
            echo json_encode(['status' => 'error', 'message' => 'Keranjang kosong.']);
            return;
        }

        $total = 0;
        $item_details = [];

        foreach ($keranjang as $item) {
            $total += $item->qty * $item->harga;
            $item_details[] = [
                'id' => $item->id_produk,
                'price' => (int)$item->harga,
                'quantity' => (int)$item->qty,
                'name' => $item->nama_produk
            ];
        }

        $total_final = $total + $biaya_kirim + $asuransi;

        // Simpan ke database
        $pesanan = [
            'pembeli_id' => $id_pembeli,
            'total_harga' => $total_final,
            'metode_pembayaran' => $metode,
            'kurir' => $kurir,
            'status' => 'pending',
            'tanggal_pembelian' => date('Y-m-d')
        ];
        $this->db->insert('pesanan', $pesanan);
        $id_pesanan = $this->db->insert_id();

        foreach ($keranjang as $item) {
            $this->db->insert('pesanan_detail', [
                'id_pesanan' => $id_pesanan,
                'produk_id'  => $item->id_produk,
                'jumlah'     => $item->qty,
                'harga'      => $item->harga
            ]);

            // Kurangi stok
            $this->db->set('stok', 'stok - ' . (int)$item->qty, false)
                     ->where('id_produk', $item->id_produk)
                     ->update('produk');
        }

        // Bersihkan keranjang & session ongkir
        $this->Keranjang_model->kosongkan($id_pembeli);
        $this->session->unset_userdata(['kurir', 'biaya_kirim']);

        // Midtrans token
        $params = [
            'transaction_details' => [
                'order_id' => 'INV-' . $id_pesanan,
                'gross_amount' => (int)$total_final
            ],
            'item_details' => $item_details,
            'customer_details' => [
                'first_name' => $this->session->userdata('nama') ?? 'Pembeli',
                'email' => $this->session->userdata('email') ?? 'user@email.com'
            ]
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            echo json_encode([
                'status' => 'success',
                'token' => $snapToken,
                'order_id' => $id_pesanan
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function sukses($id_pesanan)
    {
        $data['pesanan'] = $this->Pesanan_model->getPesanan($id_pesanan);
        $data['items'] = $this->Pesanan_model->getDetailPesanan($id_pesanan);
        if (!$data['pesanan']) show_404();

        $this->load->view('homepage/header');
        $this->load->view('homepage/menu');
        $this->load->view('admin/pembeli/Pesanan_detail', $data);
        $this->load->view('homepage/footer');
    }
}
