
  <div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
      <div class="container">
        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
        <a class="brand" href="<?php echo base_url(); ?>">R3D Align</a>
        <div class="nav-collapse collapse">
          <ul class="nav">
            <li><a href="<?php echo site_url('gallery');?>">Gallery of Featured Alignments</a></li>
            <li><a href="https://github.com/BGSU-RNA/R3DAlign">Standalone Program</a></li>
            <li><a href="<?=$this->config->item('home_url')?>/main/r3dalign-help" target="_blank">Help</a></li>
            <li><a href="<?=$this->config->item('home_url')?>/main/contact-us">Contact Us</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                RNA 3D Hub
                <b class="caret"></b>
              </a>
              <ul class="dropdown-menu">
                <li><a href="<?=$this->config->item('home_url')?>/rna3dhub/pdb">RNA Structure Atlas</a></li>
                <li><a href="<?=$this->config->item('home_url')?>/rna3dhub/nrlist">Non-redundant Lists</a></li>
                <li><a href="<?=$this->config->item('home_url')?>/rna3dhub/motifs">RNA 3D Motif Atlas</a></li>
                <li class="divider"></li>
                <li><a href="<?=$this->config->item('home_url')?>/rna3dhub">RNA 3D Hub Home</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                Resources
                <b class="caret"></b>
              </a>
              <ul class="dropdown-menu">
                <li><a href="<?=$this->config->item('home_url')?>">RNA BGSU Home</a></li>
                <li class="divider"></li>
                <li><a href="<?=$this->config->item('home_url')?>/FR3D/basepairs">Basepair Catalog</a></li>
                <li><a href="<?=$this->config->item('home_url')?>/webfr3d">WebFR3D</a></li>
                <li><a href="<?=$this->config->item('home_url')?>/jar3d">JAR3D</a></li>
                <li class="divider"></li>
                <li><a href="https://github.com/BGSU-RNA/WebR3DAlign">Github</a></li>
              </ul>
            </li>

          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
  </div>

