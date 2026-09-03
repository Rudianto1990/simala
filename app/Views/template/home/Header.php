<!-- ======= Header ======= -->
<header id="header">
    <div class="container d-flex">

        <div class="logo mr-auto">
            <h1 class="text-light"><a href="/"><span><?= $appname; ?></span></a></h1>
            <!-- Uncomment below if you prefer to use an image logo -->
            <!-- <a href="index.html"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->
        </div>

        <nav class="nav-menu d-none d-lg-block">
            <ul>
                <li class="active"><a href="/">Home</a></li>
                <li><a href="<?= base_url('dashboard/'); ?>">Administrator</a></li>
                <li class="drop-down"><a href="">Menu</a>
                    <ul>
                        <li class="drop-down"><a href="#">Data Alat</a>
                            <ul>
                                <li><a href="<?= base_url('?jenjang=rtg') ?>">Rubber Tyred Gantry (RTG)</a></li>
                                
                            </ul>
                        </li>
                        <li><a href="<?= base_url('table'); ?>">Table</a></li>
                    </ul>
                </li>
            </ul>
        </nav><!-- .nav-menu -->

    </div>
</header><!-- End Header -->