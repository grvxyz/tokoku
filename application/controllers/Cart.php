<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends CI_Controller {

    private $id_pembeli;

    public function __construct() {
        parent::__construct();
        $this->load->model('Cart_model');
        $this->load->library('session');
        $this->load->helper(['url', 'form']);

        // Ambil ID pembeli dari session atau fallback sementara
        $this->id_pembeli = $this->session->userdata('id_pembeli') ?? 23122774;
    }

    public function index() {
        $data['title'] = "Keranjang Belanja";
        $data['items'] = $this->Cart_model->get_cart_items($this->id_pembeli);

        $total = 0;
        foreach ($data['items'] as $item) {
            $total += $item->qty * $item->harga_produk;
        }
        $data['total'] = $total;

        $this->load->view('homepage/header', $data);
        $this->load->view('homepage/menu');
        $this->load->view('admin/pembeli/cart', $data); // pastikan path-nya benar
        $this->load->view('homepage/footer');
    }

    public function add() {
        $id_produk = $this->input->post('id_produk');
        $qty       = (int) $this->input->post('qty');
        $harga     = (float) $this->input->post('harga');

        if ($id_produk && $qty > 0 && $harga > 0) {
            $data = [
                'id_pembeli' => $this->id_pembeli,
                'id_produk'  => $id_produk,
                'qty'        => $qty,
                'harga'      => $harga
            ];
            $this->Cart_model->add_to_cart($data);
        }

        redirect('cart');
    }

    public function update_ajax() {
        $id_keranjang = $this->input->post('id_keranjang');
        $qty          = (int) $this->input->post('qty');

        if ($id_keranjang && $qty > 0) {
            $this->Cart_model->update_qty($id_keranjang, $qty);

            $items = $this->Cart_model->get_cart_items($this->id_pembeli);
            $subtotal = 0;

            foreach ($items as $item) {
                if ($item->id_keranjang == $id_keranjang) {
                    $subtotal = $item->harga_produk * $item->qty;
                }
            }

            $total = array_reduce($items, function($carry, $item) {
                return $carry + ($item->harga_produk * $item->qty);
            }, 0);

            echo json_encode([
                'status'   => 'success',
                'subtotal' => number_format($subtotal, 0, ',', '.'),
                'total'    => number_format($total, 0, ',', '.')
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak valid']);
        }
    }

    public function update() {
        $id_keranjang = $this->input->post('id_keranjang');
        $qty          = (int) $this->input->post('qty');

        if ($id_keranjang && $qty > 0) {
            $this->Cart_model->update_qty($id_keranjang, $qty);
        }

        redirect('cart');
    }

    public function delete($id) {
        if ($id) {
            $this->Cart_model->remove_from_cart($id);
        }
        redirect('cart');
    }

    public function clear() {
        $this->Cart_model->clear_cart($this->id_pembeli);
        redirect('cart');
    }
}
