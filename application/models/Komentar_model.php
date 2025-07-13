<?php
class Komentar_model extends CI_Model
{
    protected $table = 'komentar';

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function get_by_produk($id_produk)
    {
        $this->db->select('komentar.*, pembeli.nama AS nama_pembeli, toko.nama_toko');
        $this->db->from($this->table);
        $this->db->join('pembeli', 'pembeli.id_pembeli = komentar.id_pembeli', 'left');
        $this->db->join('toko', 'toko.id_toko = komentar.id_toko', 'left');
        $this->db->where('id_produk', $id_produk);
        $this->db->order_by('tanggal', 'ASC');
        $query = $this->db->get();

        $comments = $query->result();

        // Mengelompokkan balasan
        $grouped = [];
        foreach ($comments as $comment) { 
            if (empty($comment->parent_id)) {
                $comment->replies = [];
                $grouped[$comment->id_komentar] = $comment;
            } else {
                if (isset($grouped[$comment->parent_id])) {
                    $grouped[$comment->parent_id]->replies[] = $comment;
                }
            }
        }

        return array_values($grouped);
    }

    public function get_by_id($id_komentar)
    {
        return $this->db->get_where($this->table, ['id_komentar' => $id_komentar])->row();
    }

    public function is_toko_owner($id_komentar, $id_toko)
    {
        $this->db->select('produk.id_toko');
        $this->db->from('komentar');
        $this->db->join('produk', 'produk.id_produk = komentar.id_produk');
        $this->db->where('komentar.id_komentar', $id_komentar);
        $query = $this->db->get();
        $result = $query->row();

        return ($result && $result->id_toko == $id_toko);
    }
}