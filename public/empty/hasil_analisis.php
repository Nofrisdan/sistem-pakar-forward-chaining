<?= $this->extend("Layout/Admin_layout"); ?>


<?= $this->section("content"); ?>
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header animate__animated animate__bounceInUp">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Halaman Analisis</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= base_url(); ?>/Analisis">Analisis</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Hasil Analisis</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="pd-20 card-box mb-30 animate__animated animate__bounceInUp">
                <div class="clearfix mb-20">
                    <div class="pull-left">
                        <h4 class="text-blue h4">Hasil Analisis</h4>

                        <p> <code class="text-primary">Nama File : </code><code><?= $file['nama_file']; ?></code></p>
                        <p><code class="text-primary">Ukuran File : </code><code><?= $file['ukuran_file']; ?></code></p>
                    </div>

                </div>
                <hr>

                <h4> <code class="text-primary">Data Deskriptif</code></h4>

                <div class="table-responsive mt-3 mb-5">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Keadaan</th>
                                <th scope="col">Mean</th>
                                <th scope="col">Modus</th>
                                <th scope="col">Median</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Normal</th>
                                <td><?= $deskriptif['normal']['mean']; ?></td>
                                <td><?= $deskriptif['normal']['median']; ?></td>
                                <td><?= $deskriptif['normal']['modus']; ?></td>

                            </tr>

                            <tr>
                                <th scope="row">Attack</th>
                                <td><?= $deskriptif['attack']['mean']; ?></td>
                                <td><?= $deskriptif['attack']['median']; ?></td>
                                <td><?= $deskriptif['attack']['modus']; ?></td>

                            </tr>

                            <tr>
                                <th scope="row">Repair</th>
                                <td><?= $deskriptif['repair']['mean']; ?></td>
                                <td><?= $deskriptif['repair']['mean']; ?></td>
                                <td><?= $deskriptif['repair']['mean']; ?></td>

                            </tr>
                        </tbody>
                    </table>
                </div>

                <h4> <code class="text-primary">Analisis Anova</code></h4>
                <hr>

                <strong><code style="font-style: italic;">Data Summary Test</code></strong>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">n</th>
                                <th scope="col">SUM</th>
                                <th scope="col">SS</th>
                                <th scope="col">Variance</th>
                                <th scope="col">Sd</th>
                                <th scope="col">Sig</th>
                            </tr>
                        </thead>
                        <tbody id="summary-data">

                            <?php foreach ($data_summary as $data) : ?>
                                <tr>
                                    <td><?= $data['n']; ?></td>
                                    <td><?= substr(round($data['sum'], 3), 0, 7); ?></td>
                                    <td><?= substr(round($data['SS'], 3), 0, 7); ?></td>
                                    <td><?= substr(round($data['variance'], 3), 0, 7); ?></td>
                                    <td><?= substr(round($data['sd'], 3), 0, 7); ?></td>
                                    <td><?= substr(round($data['sem'], 3), 0, 7); ?></td>

                                </tr>

                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <strong><code style="font-style: italic;">Total Summary Test</code></strong>


                <div class="table-responsive">
                    <div class="table-responsive mt-2 table-striped">
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">n</th>
                                    <th scope="col">SUM</th>
                                    <th scope="col">SS</th>
                                    <th scope="col">Variance</th>
                                    <th scope="col">Sd</th>
                                    <th scope="col">Sig</th>
                                </tr>
                            </thead>
                            <tbody id="total-summary">
                                <tr>
                                    <td><?= $total_summary['n']; ?></td>
                                    <td><?= substr(round($total_summary['sum'], 3), 0, 9); ?></td>
                                    <td><?= substr(round($total_summary['SS'], 3), 0, 9); ?></td>
                                    <td><?= substr(round($total_summary['variance'], 3), 0, 9); ?></td>
                                    <td><?= substr(round($total_summary['sd'], 3), 0, 9); ?></td>
                                    <td><?= substr(round($total_summary['sem'], 3), 0, 9); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <strong><code style="font-style: italic;">ANOVA RESULT</code></strong>


                <div class="table-responsive mt-3 mb-5">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">SS</th>
                                <th scope="col">df</th>
                                <th scope="col">MS</th>
                                <th scope="col">F</th>
                                <th scope="col">Sig.</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Beetwen Groups</th>
                                <td><?= substr(round($anova['treatment']['SS'], 3), 0, 9) ?></td>
                                <td><?= substr(round($anova['treatment']['df'], 3), 0, 9) ?></td>
                                <td><?= substr(round($anova['treatment']['MS'], 3), 0, 9) ?></td>
                                <td><?= substr(round($anova['treatment']['F'], 3), 0, 9) ?></td>
                                <td><?= substr(round($anova['treatment']['P'], 3), 0, 9) ?></td>

                            </tr>

                            <tr>
                                <th scope="row">Within Groups</th>
                                <td><?= substr(round($anova['error']['SS'], 3), 0, 9) ?></td>
                                <td><?= substr(round($anova['error']['df'], 3), 0, 9) ?></td>
                                <td><?= substr(round($anova['error']['MS'], 3), 0, 9) ?></td>
                                <td></td>
                                <td></td>

                            </tr>

                            <tr>
                                <th scope="row">Total</th>
                                <td><?= substr(round($anova['total']['SS'], 3), 0, 9); ?></td>
                                <td><?= substr(round($anova['total']['df'], 3), 0, 9); ?></td>
                                <td></td>
                                <td></td>
                                <td></td>

                            </tr>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>



        <?= $this->endSection("content"); ?>
        <?= $this->section("script"); ?>
        <?= $this->endSection("script"); ?>
