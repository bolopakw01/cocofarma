<?php

if (!function_exists('generate_breadcrumb')) {
    /**
     * Generate breadcrumb array based on current route
     *
     * @return array
     */
    function generate_breadcrumb()
    {
        $request = request();
        $route = $request->route();

        if (!$route || !$route->getName()) {
            return [
                ['title' => 'BackOffice', 'url' => route('backoffice.dashboard')]
            ];
        }

        $routeName = $route->getName();

        $breadcrumb = [
            ['title' => 'BackOffice', 'url' => route('backoffice.dashboard')]
        ];

        // Parse route name to build breadcrumb
        $parts = explode('.', $routeName);

        if (count($parts) >= 2 && $parts[0] === 'backoffice') {
            $module = $parts[1];

            switch ($module) {
                case 'master-produk':
                    $breadcrumb[] = ['title' => 'Master Produk', 'url' => route('backoffice.master-produk.index')];
                    if (isset($parts[2])) {
                        switch ($parts[2]) {
                            case 'create':
                                $breadcrumb[] = ['title' => 'Tambah Produk'];
                                break;
                            case 'edit':
                                $breadcrumb[] = ['title' => 'Edit Produk'];
                                break;
                            case 'show':
                                $breadcrumb[] = ['title' => 'Detail Produk'];
                                break;
                        }
                    }
                    break;

                case 'master-bahan':
                    $breadcrumb[] = ['title' => 'Master Bahan Baku', 'url' => route('backoffice.master-bahan.index')];
                    if (isset($parts[2])) {
                        switch ($parts[2]) {
                            case 'create':
                                $breadcrumb[] = ['title' => 'Tambah Bahan Baku'];
                                break;
                            case 'edit':
                                $breadcrumb[] = ['title' => 'Edit Bahan Baku'];
                                break;
                            case 'show':
                                $breadcrumb[] = ['title' => 'Detail Bahan Baku'];
                                break;
                        }
                    }
                    break;

                case 'master-user':
                    $breadcrumb[] = ['title' => 'Master User', 'url' => route('backoffice.master-user.index')];
                    if (isset($parts[2])) {
                        switch ($parts[2]) {
                            case 'create':
                                $breadcrumb[] = ['title' => 'Tambah User'];
                                break;
                            case 'edit':
                                $breadcrumb[] = ['title' => 'Edit User'];
                                break;
                            case 'show':
                                $breadcrumb[] = ['title' => 'Detail User'];
                                break;
                        }
                    }
                    break;

                case 'pesanan':
                    $breadcrumb[] = ['title' => 'Pesanan', 'url' => route('backoffice.pesanan.index')];
                    if (isset($parts[2])) {
                        switch ($parts[2]) {
                            case 'create':
                                $breadcrumb[] = ['title' => 'Tambah Pesanan'];
                                break;
                            case 'edit':
                                $breadcrumb[] = ['title' => 'Edit Pesanan'];
                                break;
                            case 'show':
                                $breadcrumb[] = ['title' => 'Detail Pesanan'];
                                break;
                        }
                    }
                    break;

                case 'bahanbaku':
                    $breadcrumb[] = ['title' => 'Bahan Baku', 'url' => route('backoffice.bahanbaku.index')];
                    if (isset($parts[2])) {
                        switch ($parts[2]) {
                            case 'create':
                                $breadcrumb[] = ['title' => 'Tambah Bahan Baku'];
                                break;
                            case 'edit':
                                $breadcrumb[] = ['title' => 'Edit Bahan Baku'];
                                break;
                            case 'show':
                                $breadcrumb[] = ['title' => 'Detail Bahan Baku'];
                                break;
                        }
                    }
                    break;

                case 'produksi':
                    $breadcrumb[] = ['title' => 'Produksi', 'url' => route('backoffice.produksi.index')];
                    if (isset($parts[2])) {
                        switch ($parts[2]) {
                            case 'create':
                                $breadcrumb[] = ['title' => 'Tambah Produksi'];
                                break;
                            case 'edit':
                                $breadcrumb[] = ['title' => 'Edit Produksi'];
                                break;
                            case 'show':
                                $breadcrumb[] = ['title' => 'Detail Produksi'];
                                break;
                        }
                    }
                    break;

                case 'transaksi':
                    $breadcrumb[] = ['title' => 'Transaksi', 'url' => route('backoffice.transaksi.index')];
                    if (isset($parts[2])) {
                        switch ($parts[2]) {
                            case 'create':
                                $breadcrumb[] = ['title' => 'Tambah Transaksi'];
                                break;
                            case 'edit':
                                $breadcrumb[] = ['title' => 'Edit Transaksi'];
                                break;
                            case 'show':
                                $breadcrumb[] = ['title' => 'Detail Transaksi'];
                                break;
                        }
                    }
                    break;

                case 'laporan':
                    $breadcrumb[] = ['title' => 'Laporan'];
                    break;

                case 'pengaturan':
                    $breadcrumb[] = ['title' => 'Pengaturan', 'url' => route('backoffice.pengaturan.index')];
                    if (isset($parts[2])) {
                        switch ($parts[2]) {
                            case 'create':
                                $breadcrumb[] = ['title' => 'Tambah Pengaturan'];
                                break;
                            case 'edit':
                                $breadcrumb[] = ['title' => 'Edit Pengaturan'];
                                break;
                        }
                    }
                    break;

                default:
                    // For dashboard or other pages
                    if ($module === 'dashboard') {
                        $breadcrumb[] = ['title' => 'Dashboard'];
                    }
                    break;
            }
        }

        return $breadcrumb;
    }
}