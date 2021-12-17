<?php

class M_barang extends CI_Model
{
    protected $_table = 'barang';

    public function lihat()
    {
        $query = $this->db->get($this->_table);
        return $query->result();
    }

    public function jumlah()
    {
        $query = $this->db->get($this->_table);
        return $query->num_rows();
    }

    public function lihat_stok()
    {
        $query = $this->db->get_where($this->_table, 'stok > 1');
        return $query->result();
    }

    public function lihat_id($kode_barang)
    {
        $query = $this->db->get_where($this->_table, ['kode_barang' => $kode_barang]);
        return $query->row();
    }

    public function lihat_nama_barang($nama_barang)
    {
        $query = $this->db->select('*');
        $query = $this->db->where(['nama_barang' => $nama_barang]);
        $query = $this->db->get($this->_table);
        return $query->row();
    }

    public function tambah($data)
    {
        return $this->db->insert($this->_table, $data);
    }

    public function min_stok($stok, $nama_barang)
    {
        $query = $this->db->set('stok', 'stok-' . $stok, false);
        $query = $this->db->where('nama_barang', $nama_barang);
        $query = $this->db->update($this->_table);
        return $query;
    }

    public function ubah()
    {
        $post = $this->input->post();
        $this->kode_barang = $post["kode_barang"]; //kita mengisi $this->id_member dengan id yang didapatkan dari form ($post['id'])
        $this->nama_barang = $post["nama_barang"];
        $this->foto_barang = $post["foto_barang"];
        $this->harga_beli = $post["harga_beli"];
        $this->harga_jual = $post["harga_jual"];
        $this->stok = $post["stok"];
        $this->satuan = $post["satuan"];
        $this->deskripsi = $post["deskripsi"];
        return $this->db->update($this->_table, $this, array('kode_barang' => $post['kode_barang']));
        // return $query;


        // 	$query = $this->db->set($data);
        // 	$query = $this->db->where(['kode_barang' => $kode_barang]);
        // 	$query = $this->db->update($this->_table);
        // 	// $this->db->ubah('barang', $data, $condition);
        // 	// return TRUE;
        // 	return $query;
    }

    public function hapus($kode_barang)
    {
        return $this->db->delete($this->_table, ['kode_barang' => $kode_barang]);
    }
}
