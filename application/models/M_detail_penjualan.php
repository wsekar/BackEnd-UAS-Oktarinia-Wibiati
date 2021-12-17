<?php

class M_detail_penjualan extends CI_Model
{
	protected $_table = 'detail_penjualan'; //tabel detail_penjualan

	public function tambah($data)
	{
		return $this->db->insert_batch($this->_table, $data);
	}

	public function lihat_no_penjualan($no_penjualan)
	{
		// result : berfungsi untuk mengambil semua data hasil query
		// datanya berdasarkan no_penjualan dalam tabel detail_penjualan
		return $this->db->get_where($this->_table, ['no_penjualan' => $no_penjualan])->result();
	}

	public function hapus($no_penjualan)
	{
		// delete/hapus data berdasarkan no penjualan
		return $this->db->delete($this->_table, ['no_penjualan' => $no_penjualan]);
	}
}
