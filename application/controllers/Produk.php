<?php
class Produk extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Produk_model', 'Kategori_model', 'Merek_model', 'Toko_model', 'Komentar_model']);
        $this->load->library('session');
        $this->load->helper('form');
    }

    private function _get_toko_id()
    {
        $id_pembeli = $this->session->userdata('id_pembeli');
        $toko = $this->Toko_model->get_by_pembeli($id_pembeli);

        if (is_array($toko)) {
            return $toko['id_toko'] ?? null;
        }

        return $toko->id_toko ?? null;
    }

    public function index()
    {
        $id_toko = $this->_get_toko_id();
        if (!$id_toko) {
            $this->session->set_flashdata('error', 'Anda belum membuat toko. Silakan buat toko terlebih dahulu.');
            redirect('toko/tambah');
        }

        $data['produk'] = $this->Produk_model->get_by_toko($id_toko);
        $this->load->view('admin/pembeli/produk/index', $data);
    }

    public function tambah()
    {
        if (!$this->session->userdata('id_pembeli')) {
            redirect('home/login');
        }

        $data['kategori'] = $this->Kategori_model->get_all_kategori();
        $data['merek'] = $this->Merek_model->get_all_merek();
        $this->load->view('admin/pembeli/produk/tambah', $data);
    }

    public function simpan()
    {
        $id_toko = $this->_get_toko_id();
        if (!$id_toko) {
            show_error('Toko tidak ditemukan atau belum dibuat.', 500);
        }

        $data = [
            'id_toko'     => $id_toko,
            'id_kategori' => $this->input->post('id_kategori'),
            'id_merek'    => $this->input->post('id_merek'),
            'nama_produk' => $this->input->post('nama_produk'),
            'deskripsi'   => $this->input->post('deskripsi'),
            'harga'       => $this->input->post('harga'),
            'stok'        => $this->input->post('stok'),
            'foto'        => $this->_upload_foto()
        ];
        $this->Produk_model->insert($data);
        redirect('produk');
    }

    public function edit($id = null)
    {
        if (!$this->session->userdata('id_pembeli')) {
            redirect('home/login');
        }

        if ($id === null) {
            show_error("ID produk tidak diberikan.", 400);
        }

        $data['produk'] = $this->Produk_model->get($id);
        $data['kategori'] = $this->Kategori_model->get_all_kategori();
        $data['merek'] = $this->Merek_model->get_all_merek(); // ✅ SESUAI dengan fungsi model

        $this->load->view('admin/pembeli/produk/edit', $data);
    }

    public function update($id)
    {
        $data = [
            'id_kategori' => $this->input->post('id_kategori'),
            'id_merek'    => $this->input->post('id_merek'),
            'nama_produk' => $this->input->post('nama_produk'),
            'deskripsi'   => $this->input->post('deskripsi'),
            'harga'       => $this->input->post('harga'),
            'stok'        => $this->input->post('stok'),
        ];
        if ($_FILES['foto']['name']) {
            $data['foto'] = $this->_upload_foto();
        }
        $this->Produk_model->update($id, $data);
        redirect('produk');
    }

    public function hapus($id)
    {
        $this->Produk_model->delete($id);
        redirect('produk');
    }

    private function _upload_foto()
    {
        $config['upload_path']   = './uploads/pembeli/produk/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 2048;
        $config['encrypt_name']  = true;

        $this->load->library('upload', $config);
        if ($this->upload->do_upload('foto')) {
            return $this->upload->data('file_name');
        }
        return null;
    }

    public function detail($id_produk)
    {
        $this->Produk_model->tambah_dilihat($id_produk);
        
        $produk = $this->Produk_model->get($id_produk);
        if (!$produk) {
            show_404();
        }

        $data['produk'] = $produk;
        $data['komentar'] = $this->Komentar_model->get_by_produk($id_produk);

        $id_pembeli = $this->session->userdata('id_pembeli');
        if ($id_pembeli) {
            $toko_pengguna = $this->Toko_model->get_by_pembeli($id_pembeli);
            $id_toko_pengguna = is_array($toko_pengguna) ?
                ($toko_pengguna['id_toko'] ?? null) :
                ($toko_pengguna->id_toko ?? null);

            $id_toko_produk = $produk->id_toko;
            $data['is_pemilik_toko'] = ($id_toko_pengguna === $id_toko_produk);
        } else {
            $data['is_pemilik_toko'] = false;
        }

        $this->load->view('admin/pembeli/produk/detail', $data);
    }

    public function tambah_komentar($id_produk)
    {
        if (!$this->session->userdata('id_pembeli')) {
            redirect('home/login');
        }

        $data = [
            'id_produk'    => $id_produk,
            'id_pembeli'   => $this->session->userdata('id_pembeli'),
            'komentar'     => $this->input->post('komentar'),
            'rating'       => $this->input->post('rating'),
            'tanggal'      => date('Y-m-d H:i:s')
        ];

        $this->Komentar_model->insert($data);
        $this->session->set_flashdata('success', 'Ulasan berhasil ditambahkan.');
        redirect('produk/detail/' . $id_produk);
    }

    public function tambah_balasan($id_komentar)
    {
        if (!$this->session->userdata('id_pembeli')) {
            redirect('home/login');
        }

        $id_toko = $this->_get_toko_id();
        if (!$id_toko) {
            show_error('Anda bukan pemilik toko', 403);
        }

        $komentar_utama = $this->Komentar_model->get_by_id($id_komentar);
        if (!$komentar_utama || !$this->Komentar_model->is_toko_owner($id_komentar, $id_toko)) {
            show_error('Komentar tidak valid atau Anda tidak memiliki akses', 403);
        }

        $data = [
            'parent_id'      => $id_komentar,
            'id_produk'      => $komentar_utama->id_produk,
            'id_toko'        => $id_toko,
            'id_pembeli'     => $this->session->userdata('id_pembeli'),
            'is_admin_reply' => 1,
            'komentar'       => $this->input->post('balasan'),
            'rating'         => null,
            'tanggal'        => date('Y-m-d H:i:s')
        ];

        $this->Komentar_model->insert($data);
        $this->session->set_flashdata('success', 'Balasan berhasil ditambahkan.');
        redirect('produk/detail/' . $komentar_utama->id_produk . '#komentar-' . $id_komentar);
    }
}
