<!-- page wrapper start -->
<div class="wrapper">
            <div class="container-fluid">

                <div class="page-title-box">
                    <div class="row align-items-center">

                        <div class="col-sm-6">
                            <h4 class="page-title">List All Biodata</h4>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Biodata</a></li>
                                <li class="breadcrumb-item active">List All</li>
                            </ol>

                        </div>
                        <div class="col-sm-6">

                            <div class="float-right d-none d-md-block">
                                <div class="dropdown">
                                    <a href="javascript:void:;" class="btn btn-primary arrow-none waves-effect waves-light btn-refresh-tbl-bio">
                                        <i class="mdi mdi-refresh mr-2"></i> Refresh Table
                                    </a>
                                    <a href="javascript:void:;" class="btn btn-primary arrow-none waves-effect waves-light">
                                        <i class="mdi mdi-file-pdf-box mr-2"></i> Convert All Data to PDF
                                    </a>
                                    <a href="<?php echo base_url('biodata/ex_excel') ?>" target="_blank" class="btn btn-primary arrow-none waves-effect waves-light">
                                        <i class="mdi mdi-file-excel mr-2"></i> Convert All Data to Excel
                                    </a>
                                    <a href="<?php echo base_url('biodata/create') ?>" class="btn btn-primary arrow-none waves-effect waves-light">
                                        <i class="mdi mdi-plus-box-outline mr-2"></i> Create New
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- Demo purpose only -->
                                <div style="min-height: 300px;">

								<h4 class="mt-0 header-title">Biodata</h4>
                                <p class="text-muted m-b-30">
									Here list of your All data Biodata
                                </p>

								<div class="table-responsive mb-0">
									<table id="datatables_bio" class="table table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
										<thead>
											<tr>
												<th class="text-center">Action</th>
												<th class="text-center">No</th>
												<th class="text-center">Nama</th>
												<th class="text-center">Tempat Tanggal Lahir</th>
												<th class="text-center">Email</th>
												<th class="text-center">No. Telp</th>
												<th class="text-center">Agama</th>
												<th class="text-center">Kebangsaan</th>
												<th class="text-center">Pendidikan</th>
												<th class="text-center">Jenis Kelamin</th>
												<th class="text-center">Status</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

            </div>
            <!-- end container-fluid -->

        </div>
        <!-- page wrapper end -->