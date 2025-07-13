<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Keranjang_model extends CI_Model {

    // ==============================
    // FUNGSI KERANJANG
    // ==============================

    // Mengambil semua isi keranjang berdasarkan ID pembeli
    public function getKeranjang($id_pembeli) {
        $this->db->select('keranjang.*, produk.nama_produk, produk.foto, produk.harga, produk.berat, kategori.nama_kategori, merek.nama_merek');
        $this->db->from('keranjang');
        $this->db->join('produk', 'produk.id_produk = keranjang.id_produk');
        $this->db->join('kategori', 'kategori.id_kategori = produk.id_kategori', 'left');
        $this->db->join('merek', 'merek.id_merek = produk.id_merek', 'left');
        $this->db->where('keranjang.id_pembeli', $id_pembeli);
        return $this->db->get()->result();
    }

    // Menambah produk ke keranjang atau update jumlah jika sudah ada
    public function tambah($data) {
        $this->db->where('id_pembeli', $data['id_pembeli']);
        $this->db->where('id_produk', $data['id_produk']);
        $query = $this->db->get('keranjang');

        if ($query->num_rows() > 0) {
            $this->db->set('qty', 'qty + ' . $data['qty'], FALSE);
            $this->db->where('id_pembeli', $data['id_pembeli']);
            $this->db->where('id_produk', $data['id_produk']);
            return $this->db->update('keranjang');
        } else {
            return $this->db->insert('keranjang', $data);
        }
    }

    // Menghapus 1 item dari keranjang
    public function hapus($id_keranjang) {
        return $this->db->delete('keranjang', ['id_keranjang' => $id_keranjang]);
    }

    // Mengosongkan seluruh isi keranjang berdasarkan pembeli
    public function kosongkan($id_pembeli) {
        return $this->db->delete('keranjang', ['id_pembeli' => $id_pembeli]);
    }

    // Mengambil data keranjang berdasarkan ID keranjang
    public function getById($id_keranjang) {
        return $this->db->get_where('keranjang', ['id_keranjang' => $id_keranjang])->row();
    }

    // Memperbarui jumlah qty produk di keranjang
    public function updateQty($id_keranjang, $qty) {
        return $this->db->update('keranjang', ['qty' => $qty], ['id_keranjang' => $id_keranjang]);
    }


    // ==============================
    // FUNGSI RAJAONGKIR (ONGKOS KIRIM)
    // ==============================

    // Ambil semua kota/distrik
    public function tampil_distrik() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/city",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array("key: c6fef2896c47fb0af278511adfc53b66"),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $responsi = json_decode($response, TRUE);
            return $responsi["rajaongkir"]["results"];
        }
    }

    // Ambil detail satu distrik/kota berdasarkan ID
    public function detail_distrik($city_id) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/city?id=$city_id",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array("key: c6fef2896c47fb0af278511adfc53b66"),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $responsi = json_decode($response, TRUE);
            return $responsi["rajaongkir"]["results"];
        }
    }

    // Hitung biaya ongkir dari origin ke destination
    public function biaya($origin, $destination, $weight) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin=$origin&destination=$destination&weight=$weight&courier=jne",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: gazdXhb06b465ef128160896PV7TR02U"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response, TRUE);
            if (isset($response['rajaongkir']['results'][0])) {
                return $response['rajaongkir']['results'][0];
            } else {
                return array(); // Jika tidak tersedia data
            }
        }
    }
}
