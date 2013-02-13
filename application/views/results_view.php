<?php
    $baseurl = base_url();
?>
<div id="wrap">
  <div class="container r3dalign-results">

    <div class="content">

      <div class="row">

        <div class="span6"> <!-- left panel -->

          <h3>Query <?=$query_id?></h3>

          <script type="text/javascript">
            jmolInitialize(" /jmol");
            jmolSetAppletColor("#f3f3f3");
            jmolApplet(450, "load <?=$baseurl?>data/results/<?=$query_id?>/<?=$query_id?>.pdb; hide a1l; spacefill off; frame all; select 1.1; color [230,159,0]; select 1.2; color translucent [230,159,0]; select 1.3; color [0,114,178]; select 1.4; color translucent [0,114,178]; select all; display all;");
          </script>

          <button class="btn" id="toggle_stereo">Stereo</button>
          <button class="btn" id="jmol_rotate_y">Rotate</button>
          <button class="btn" id="toggle_labels">Labels on</button>
          <button class="btn" id="toggle_aligned">Hide unaligned nucleotides</button>

          <p>
          <small>Unaligned nucleotides are translucent.</small>
          </p>

        </div> <!-- span6, left panel -->

        <div class="span6" id="tabbed_content"> <!-- right panel -->

            <!-- tab menu -->
            <ul class="nav nav-tabs" id="nav">
              <li class="active"><a href="#overview">Overview</a></li>
              <li><a href="#basepairs">Basepairs</a></li>
              <li><a href="#alignment">Alignment</a></li>

              <li class="dropdown">
                <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#">
                Download <b class="caret"></b></a>
                <ul class="dropdown-menu" role="menu">
                  <?php if (file_exists($results_folder . "$query_id/$query_id.pdb")): ?>
                    <li><a href="<?=$baseurl?>data/results/<?=$query_id?>/<?=$query_id?>.pdb"
                           target='_blank' download="<?=$query_id?>.pdb">3D superposition (.pdb)</a>
                    </li>
                  <?php endif; ?>

                  <?php if (file_exists($results_folder . "$query_id/$query_id.fasta")): ?>
                    <li><a href="<?=$baseurl?>data/results/<?=$query_id?>/<?=$query_id?>.fasta"
                           target='_blank' download="<?=$query_id?>.fasta">3D alignment (.fasta)</a>
                    </li>
                  <?php endif; ?>

                  <?php if (file_exists($results_folder . "$query_id/$query_id.pdf")): ?>
                    <li><a href="<?=$baseurl?>data/results/<?=$query_id?>/<?=$query_id?>.pdf"
                           target='_blank' download="<?=$query_id?>.pdf">Standard Bar Diagram (.pdf)</a>
                    </li>
                  <?php endif; ?>

                  <?php if (file_exists($results_folder . "$query_id/$query_id" . "_int.pdf")): ?>
                    <li><a href="<?=$baseurl?>data/results/<?=$query_id?>/<?=$query_id?>_int.pdf"
                           target='_blank' download="<?=$query_id?>_int.pdf">Basepair Bar Diagram (.pdf)</a>
                    </li>
                  <?php endif; ?>

                  <?php if (file_exists($results_folder . "$query_id/$query_id.csv")): ?>
                    <li><a href="<?=$baseurl?>data/results/<?=$query_id?>/<?=$query_id?>.csv"
                           target='_blank' download="<?=$query_id?>.csv">Basepair comparison (.csv)</a>
                    </li>
                  <?php endif; ?>
                </ul>
              </li>
            </ul>

            <?php
                $bar_diagram = '';
                $basefile = $results_folder . "$query_id/$query_id";
                if (file_exists("$basefile.png")) {
                    $bar_diagram = "$query_id.png";
                } elseif (file_exists("$basefile.jpg")) {
                    $bar_diagram = "$query_id.jpg";
                }

                $advanced_diagram = '';
                if (file_exists($basefile . "_int.png")) {
                    $advanced_diagram = $basefile . "_int.png";
                }
            ?>

            <!-- tab content -->
            <div class="tab-content">

              <!-- overview panel -->
              <div class="tab-pane active" id="overview">
                <h4>Local geometric similarity</h4>

                <?php if ($bar_diagram != ''): ?>

                <small>
                <ul class="nav nav-tabs" id="nav-diagrams">
                  <li class="active"><a href="#standard-diagram">Standard Bar Diagram</a></li>
                  <li><a href="#advanced-diagram">Basepair Bar Diagram</a></li>
                </ul>
                </small>

                <div class="tab-content">

                  <div class="tab-pane active" id="standard-diagram">
                    <ul class="thumbnails">
                      <li>
                        <a href="#" class="thumbnail">
                          <img src="<?=$baseurl?>data/results/<?=$query_id?>/<?=$bar_diagram?>" class="fancybox r3dalign-results-crop span6">
                        </a>
                      </li>
                    </ul>
                  </div>

                  <div class="tab-pane" id="advanced-diagram">

                  <?php if ( $advanced_diagram != '' ): ?>
                    <ul class="thumbnails">
                      <li>
                        <a href="#" class="thumbnail">
                          <img src="<?=$baseurl?>data/results/<?=$query_id?>/<?=$query_id?>_int.png" class="fancybox span6">
                        </a>
                      </li>
                    </ul>
                  <?php else: ?>
                    Advanced diagram is not available for this query.
                  <?php endif; ?>
                  </div>

                </div>

                <small>
                  <i class="icon-info-sign"></i> Blue (low geometric discrepancy) indicates high 3D similarity.
                  Red (high geometric discrepancy) indicates low 3D similarity.
                  <a href="http://rna.bgsu.edu/main/r3dalign-help/r3dalign-bar-diagram/" target="_blank">More</a>
                </small>

                <?php else: ?>
                  Bar diagram is not available for this query.
                <?php endif; ?>

                <br>

                <dl>
                  <dt style="color: rgb(230,159,0)">Molecule 1</dt>
                  <?php if ($parameters['pdb_uploaded1']): ?>
                    <dd>user supplied file <strong><?=$parameters['pdb_uploaded_filename1']?></strong></dd>
                  <?php else: ?>
                    <dd>
                      <strong>PDB id:</strong> <a class="pdb_info"><?=$parameters['pdb1']?></a>,
                      <strong>Chains:</strong> <?=$parameters['chains1']?>,
                      <strong>Nucleotides:</strong> <?=$parameters['nts1']?>
                    </dd>
                  <?php endif; ?>

                  <dt style="color: rgb(0,114,178)">Molecule 2</dt>
                  <?php if ($parameters['pdb_uploaded2']): ?>
                    <dd>user supplied file <strong><?=$parameters['pdb_uploaded_filename2']?></strong></dd>
                  <?php else: ?>
                    <dd>
                      <strong>PDB id:</strong> <a class="pdb_info"><?=$parameters['pdb2']?></a>,
                      <strong>Chains:</strong> <?=$parameters['chains2']?>,
                      <strong>Nucleotides:</strong> <?=$parameters['nts2']?>
                    </dd>
                  <?php endif; ?>

                  <dt>Iteration 1</dt>
                  <dd>
                      d = <?=$parameters['discrepancy1']?>,
                      p = <?=$parameters['neighborhoods1']?>,
                      &beta; = <?=$parameters['bandwidth1']?>
                  </dd>

                  <?php if ($parameters['iteration2'] == 1): ?>
                  <dt>Iteration 2</dt>
                  <dd>
                      d = <?=$parameters['discrepancy2']?>,
                      p = <?=$parameters['neighborhoods2']?>,
                      &beta; = <?=$parameters['bandwidth2']?>
                  </dd>
                  <?php endif; ?>

                  <?php if ($parameters['iteration3'] == 1): ?>
                  <dt>Iteration 3</dt>
                  <dd>
                      d = <?=$parameters['discrepancy3']?>,
                      p = <?=$parameters['neighborhoods3']?>,
                      &beta; = <?=$parameters['bandwidth3']?>
                  </dd>
                  <?php endif; ?>

                </dl>

                <!-- AddThis Button BEGIN -->
                <span class="addthis_toolbox addthis_default_style">
                <a href="http://www.addthis.com/bookmark.php?v=250&amp;pubid=ra-4fb19f2d543502ad" class="addthis_button_compact at300m"><span class="at16nc at300bs at15nc at15t_compact at16t_compact"><span class="at_a11y">More Sharing Services</span></span>Share</a>
                <div class="atclear"></div>
                </span>
                <script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
                <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4fb19f2d543502ad"></script>
                <!-- AddThis Button END -->

              </div> <!-- overview panel -->

              <div class="tab-pane" id="basepairs">
                  <?php if ( is_null($basepair_table) ) : ?>
                    Basepair comparison is not available for this query. Please
                    send us the query id if you believe this is a mistake.
                  <?php else: ?>

                            <table id='legend' class="span6">
                            <caption>Discrepancy color legend (low is better)</caption>
                            <tr>
                            <td class="legend d10">&lt;0.1</td>
                            <td class="legend d09">&lt;0.2</td>
                            <td class="legend d08">&lt;0.3</td>
                            <td class="legend d07">&lt;0.4</td>
                            <td class="legend d06">&lt;0.5</td>
                            <td class="legend d05">&lt;0.6</td>
                            <td class="legend d04">&lt;0.7</td>
                            <td class="legend d03">&lt;0.8</td>
                            <td class="legend d02">&lt;0.9</td>
                            <td class="legend d01">&lt;1.0</td>
                            <td class="legend d00">&gt;=1.1</td>
                            </tr>
                            </table>
                    <table class="table table-bordered table-condensed basepair-table">
                    <?=$basepair_table;?>
                    </table>
                  <?php endif ?>
              </div>

              <div class="tab-pane" id="alignment">
                  <?php if ( is_null($basepair_table) ) : ?>
                    Alignment is not available for this query. Please
                    send us the query id if you believe this is a mistake.
                  <?php else: ?>
                    <pre><?=$alignment?></pre>
                  <?php endif ?>
              </div>
          </div> <!-- tab-content -->
        </div> <!-- span6, right panel -->
      </div> <!-- row -->

    </div> <!-- content -->

  </div> <!-- container -->
</div> <!-- wrap -->

<script type="text/javascript" src="<?php echo base_url(); ?>js/main.js"></script>
<script>
    // activate tooltips
    $('i').tooltip();

    $('.pdb_info').click(LookUpPDBInfo);

    $('.fancybox').fancybox({
        afterClose: function(){
            $('img').css('display', 'inline').addClass('span6');
            $('#jmolApplet0').show();
        },
        beforeShow: function(){
            $('img').removeClass('span6');
            $('#jmolApplet0').hide();
        },
        autoSize: false
    });

    // activate main tab navigation
    $('#nav>li>a').click(function (e) {
      e.preventDefault();
      $(this).tab('show');
    });

    // activate diagrmas tab navigation
    $('#nav-diagrams>li>a').click(function (e) {
      e.preventDefault();
      $(this).tab('show');
    })

    $('#toggle_stereo').toggle(function(){
        jmolScript("stereo on");
    }, function(){
        jmolScript("stereo off");
    });

    $('#toggle_aligned').toggle(function(){
        jmolScript("frame all; display displayed and not 1.2; display displayed and not 1.4;");
        $(this).html('Show unaligned nucleotides');
    }, function(){
        jmolScript("frame all; display displayed or 1.2; display displayed or 1.4;");
        $(this).html('Hide unaligned nucleotides');
    });

    $('#toggle_labels').toggle(function(){
        jmolScript('select *.C5;label "%n%R";color labels black;');
        $(this).html('Labels off');
    }, function() {
        jmolScript("labels off");
        $(this).html('Labels on');
    });

    $('#jmol_rotate_y').on('click', function(){
        jmolScript("move 0 360 0 0 0 0 0 0 2");
    });
</script>
