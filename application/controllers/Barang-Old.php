<?php

use Dompdf\Dompdf;

class Barang extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if ($this->session->login['role'] != 'kasir' && $this->session->login['role'] != 'admin') redirect();
		$this->data['aktif'] = 'barang';
		$this->load->model('M_barang', 'm_barang');
		$this->load->model('M_kategori');
		$this->load->library('upload');
	}

	public function index()
	{
		$this->data['title'] = 'Data Barang';
		$this->data['all_barang'] = $this->m_barang->lihat();
		$this->data['no'] = 1;

		$this->load->view('barang/lihat', $this->data);
	}

	public function tambah()
	{
		if ($this->session->login['role'] == 'kasir') {
			$this->session->set_flashdata('error', 'Tambah data hanya untuk admin!');
			redirect('penjualan');
		}

		$this->data['title'] = 'Tambah Barang';

		$this->load->view('barang/tambah', $this->data);
	}

	public function proses_tambah()
	{
		if ($this->session->login['role'] == 'kasir') {
			$this->session->set_flashdata('error', 'Tambah data hanya untuk admin!');
			redirect('penjualan');
		}
		if ($this->input->method() === 'post') {
			$kode_barang = $this->input->post('kode_barang', TRUE);
			$nama_barang = $this->input->post('nama_barang', TRUE);
			$harga_beli = $this->input->post('harga_beli', TRUE);
			$harga_jual = $this->input->post('harga_jual', TRUE);
			$stok = $this->input->post('stok', TRUE);
			$satuan = $this->input->post('satuan', TRUE);
			// $deskripsi = $this->input->post('deskripsi', TRUE); wysiwyg, blm ditambahkan
			$config['upload_path']          = './assets/images/'; //tempat penyimpanan
			$config['allowed_types']        = 'gif|jpg|png|PNG'; //tipe yang ingin diinsert
			$config['max_size']             = 10000; //ukuran file maksimal
			$config['max_width']            = 10000; //lebar maksimal
			$config['max_height']           = 10000; //tinggi maksimal
			$config['file_name'] = $_FILES['foto_barang']['name'];
			$this->upload->initialize($config);
			if (!empty($_FILES['foto_barang']['name'])) {
				if ($this->upload->do_upload('foto_barang')) {
					$foto_barang = $this->upload->data();
					$data = array(
						'foto_barang' => $foto_barang['file_name'],
						'kode_barang' => $kode_barang,
						'nama_barang' => $nama_barang,
						'harga_beli' => $harga_beli,
						'harga_jual' => $harga_jual,
						'stok' => $stok,
						'satuan' => $satuan,
						// 'deskripsi' => $deskripsi,
					);
					// $kategori_barang = count($this->input->post('nama_kategori_hidden'));
					// $data = [
					// 	'kode_barang' => $this->input->post('kode_barang'),
					// 	'nama_barang' => $this->input->post('nama_barang'),
					// 	'harga_beli' => $this->input->post('harga_beli'),
					// 	'harga_jual' => $this->input->post('harga_jual'),
					// 	'stok' => $this->input->post('stok'),
					// 	'satuan' => $this->input->post('satuan'),


					// ];
					// $daftar_kategori = [];

					// for ($i = 0; $i < $kategori_barang; $i++) {
					// 	array_push($daftar_kategori, ['nama_kategori' => $this->input->post('nama_kategori_hidden')[$i]]);
					// }

					if ($this->m_barang->tambah($data)) {
						$this->session->set_flashdata('success', 'Data Barang <strong>Berhasil</strong> Ditambahkan!');
						redirect('barang');
					} else {
						$this->session->set_flashdata('error', 'Data Barang <strong>Gagal</strong> Ditambahkan!');
						redirect('barang');
					}
				} else {
					$this->load->view("barang/tambah");
				}
			}
		}
	}


	public function ubah($kode_barang)
	{
		if ($this->session->login['role'] == 'kasir') {
			$this->session->set_flashdata('error', 'Ubah data hanya untuk admin!');
			redirect('penjualan');
		}

		$this->data['title'] = 'Ubah Barang';
		$this->data['barang'] = $this->m_barang->lihat_id($kode_barang);

		$this->load->view('barang/ubah', $this->data);
	}

	public function proses_ubah($kode_barang)
	{
		if ($this->session->login['role'] == 'kasir') {
			$this->session->set_flashdata('error', 'Ubah data hanya untuk admin!');
			redirect('penjualan');
		}

		$data = [
			'kode_barang' => $this->input->post('kode_barang'),
			'nama_barang' => $this->input->post('nama_barang'),
			'harga_beli' => $this->input->post('harga_beli'),
			'harga_jual' => $this->input->post('harga_jual'),
			'stok' => $this->input->post('stok'),
			'satuan' => $this->input->post('satuan'),
		];

		if ($this->m_barang->ubah($data, $kode_barang)) {
			$this->session->set_flashdata('success', 'Data Barang <strong>Berhasil</strong> Diubah!');
			redirect('barang');
		} else {
			$this->session->set_flashdata('error', 'Data Barang <strong>Gagal</strong> Diubah!');
			redirect('barang');
		}
	}

	public function hapus($kode_barang)
	{
		if ($this->session->login['role'] == 'kasir') {
			$this->session->set_flashdata('error', 'Hapus data hanya untuk admin!');
			redirect('penjualan');
		}

		if ($this->m_barang->hapus($kode_barang)) {
			$this->session->set_flashdata('success', 'Data Barang <strong>Berhasil</strong> Dihapus!');
			redirect('barang');
		} else {
			$this->session->set_flashdata('error', 'Data Barang <strong>Gagal</strong> Dihapus!');
			redirect('barang');
		}
	}

	public function export()
	{
		$dompdf = new Dompdf();
		// $this->data['perusahaan'] = $this->m_usaha->lihat();
		$this->data['all_barang'] = $this->m_barang->lihat();
		$this->data['title'] = 'Laporan Data Barang';
		$this->data['no'] = 1;

		$dompdf->setPaper('A4', 'Landscape');
		$html = $this->load->view('barang/report', $this->data, true);
		$dompdf->load_html($html);
		$dompdf->render();
		$dompdf->stream('Laporan Data Barang Tanggal ' . date('d F Y'), array("Attachment" => false));
	}
}
