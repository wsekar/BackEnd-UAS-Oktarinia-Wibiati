<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pdfview extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->login['role'] != 'kasir' && $this->session->login['role'] != 'admin') redirect();
        $this->data['aktif'] = 'barang';
        $this->load->model('M_barang', 'm_barang');
    }

    public function index()
    {
        $this->data['title'] = 'Data Barang';
        $this->data['all_barang'] = $this->m_barang->lihat();
        $this->data['no'] = 1;

        $this->load->view('barang/lihat', $this->data);
    }

    public function laporan_pdf()
    {
        // panggil library yang kita buat sebelumnya yang bernama pdfgenerator
        $this->load->library('pdfgenerator');

        // title dari pdf
        $this->data['all_barang'] = $this->m_barang->lihat();
        $this->data['title'] = 'Laporan Data Barang';
        $this->data['no'] = 1;

        // filename dari pdf ketika didownload
        $file_pdf = 'laporan_penjualan_toko_kita';
        // setting paper
        $paper = 'A4';
        //orientasi paper potrait / landscape
        $orientation = "portrait";

        $html = $this->load->view('laporan_pdf', $this->data, true);

        // run dompdf
        $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
    }
}
