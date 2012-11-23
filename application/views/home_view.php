<div id="wrap">
  <div class="container r3dalign-homepage">
    <div class="content">
      <form>
      <div class="row well well-small"> <!-- query 1 -->
        <div class="span5"> <!-- structure 1 controls -->
          <h4>First Structure</h4>
          <select class="pdb1" data-placeholder="Choose a structure from PDB">
            <option></option>
            <?php foreach ($pdbs as $pdb): ?>
              <option value="<?=$pdb?>"><?=$pdb?></option>
            <?php endforeach; ?>
          </select>
          or <button class="btn" id="upload1">upload PDB file</button>
          <br>
          <div id="mol1_info_fragments"></div>
        </div> <!-- structure 1 controls -->

        <div class="span6"> <!-- structure 1 info -->
          <div class="row">
              <div id="mol1_info" class="small">
                <div class="span3">
                  <dl>
                    <dt>Title</dt>
                    <dd id="mol1_info_title"></dd>
                    <dt>Experimental details</dt>
                    <dd id="mol1_info_resolution"></dd>
                    <dd id="mol1_info_technique"></dd>
                  </dl>
                  <a href="#" target="_blank" id="mol1_info_rna3dhub_link">RNA 3D Hub</a> |
                  <a href="#" target="_blank" id="mol1_info_pdb_link">PDB</a> |
                  <a href="#" target="_blank" id="mol1_info_ndb_link">NDB</a>
                </div>
                <div class="span3">
                  <dl>
                    <dt>Similar structures</dt>
                    <dd id="mol1_info_similar"></dd>
                  </dl>
                  Structures from <a id="mol1_info_eq_class" href="#" target="_blank"></a>
                </div>
              </div>
          </div>
        </div> <!-- structure 1 info -->
      </div> <!-- row query 1 -->

      <div class="row well well-small"> <!-- query 2 -->
        <div class="span5"> <!-- structure 2 conrols-->
          <h4>Second Structure</h4>
          <select class="pdb2" data-placeholder="Choose second PDB id">
            <option></option>
            <?php foreach ($pdbs as $pdb): ?>
              <option value="<?=$pdb?>"><?=$pdb?></option>
            <?php endforeach; ?>
          </select>
          or <button class="btn" id="upload2">upload PDB file</button>
          <br>
          <div id="mol2_info_fragments"></div>
        </div> <!-- structure 2 conrols-->

        <div class="span6"> <!-- structure 2 info -->
          <div class="row">
              <div id="mol2_info" class="small">
                <div class="span3">
                  <dl>
                    <dt>Title</dt>
                    <dd id="mol2_info_title"></dd>
                    <dt>Experimental details</dt>
                    <dd id="mol2_info_resolution"></dd>
                    <dd id="mol2_info_technique"></dd>
                  </dl>
                  <a href="#" target="_blank" id="mol2_info_rna3dhub_link">RNA 3D Hub</a> |
                  <a href="#" target="_blank" id="mol2_info_pdb_link">PDB</a> |
                  <a href="#" target="_blank" id="mol2_info_ndb_link">NDB</a>
                </div>
                <div class="span3">
                  <dl>
                    <dt>Similar structures</dt>
                    <dd id="mol2_info_similar"></dd>
                  </dl>
                  Structures from <a id="mol2_info_eq_class" href="#" target="_blank"></a>
                </div>
              </div>
          </div>
        </div> <!-- structure 2 info-->
      </div> <!-- row query 2 -->

      <div class="row well well-small"> <!-- form controls -->
          <div class="span12 ">
            <button type="button" class="btn" id="toggle_advanced">Show advanced options</button>
            <button type="button" class="btn">Reset</button>
            <input type="text" class="" placeholder="Email (optional)">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
      </div> <!-- row form controls -->

      <div class="row advanced-options"> <!-- Advanced options-->
        <div class="span3 well well-small" id="iteration1">
          <fieldset>
            <h5>Iteration 1</h5>

            <input type="text" class="r3dalign-input-mini" value="0.5">
            <span class="help-inline">Discrepancy Cutoff (d) <i class="icon-info-sign"></i></span>
            <br>

            <input type="text" class="r3dalign-input-mini" value="7">
            <span class="help-inline">Neighborhoods/nucleotide (p) <i class="icon-info-sign"></i></span>
            <br>

            <input type="text" class="r3dalign-input-mini" value="60">
            <span class="help-inline">Seed alignment bandwidth (&beta;) <i class="icon-info-sign"></i></span>
            <br>

            <strong>Final clique-finding method:</strong> <i class="icon-info-sign"></i>
            <label class="radio">
              <input type="radio" name="cliqueMethod1" value="greedy" checked>
              Greedy (Faster)
            </label>
            <label class="radio">
              <input type="radio" name="cliqueMethod1" value="full">
              Branch and Bound (Exact)
            </label>

            <strong>Seed alignment:</strong> <i class="icon-info-sign"></i>
            <label class="radio">
              <input type="radio" name="seed1" id="optionsRadios1" value="NWseed" checked>
              Internally produced alignment
            </label>
            <label class="radio">
              <input type="radio" name="seed1" id="optionsRadios2" value="Manual">
              Upload seed alignment (fasta):
            </label>

            <label class="checkbox">
              <input type="checkbox" id="toggle_iteration2"> Use this alignment as the seed for next iteration?
            </label>
          </fieldset>
        </div>

        <div class="span3 well well-small" id="iteration2">
          <fieldset>
            <h5>Iteration 2</h5>

            <input type="text" class="r3dalign-input-mini" value="0.5">
            <span class="help-inline">Discrepancy Cutoff (d)</span>
            <br>

            <input type="text" class="r3dalign-input-mini" value="7">
            <span class="help-inline">Neighborhoods/nucleotide (p)</span>
            <br>

            <input type="text" class="r3dalign-input-mini" value="60">
            <span class="help-inline">Seed alignment bandwidth (&beta;)</span>
            <br>

            <strong>Final clique-finding method:</strong>
            <label class="radio">
              <input type="radio" name="cliqueMethod2" id="optionsRadios1" value="greedy" checked>
              Greedy (Faster)
            </label>
            <label class="radio">
              <input type="radio" name="cliqueMethod2" id="optionsRadios2" value="full">
              Branch and Bound (Exact)
            </label>

            <strong>Seed alignment:</strong>
            <label class="radio">
              <input type="radio" name="seed2" id="optionsRadios1" value="NWseed" checked>
              Internally produced alignment
            </label>
            <label class="radio">
              <input type="radio" name="seed2" id="optionsRadios2" value="Manual">
              Upload seed alignment (fasta):
            </label>

            <label class="checkbox">
              <input type="checkbox" id="toggle_iteration3"> Use this alignment as the seed for next iteration?
            </label>
          </fieldset>
        </div>

        <div class="span3 well well-small" id="iteration3">
          <fieldset>
            <h5>Iteration 3</h5>

            <input type="text" class="r3dalign-input-mini" value="0.5">
            <span class="help-inline">Discrepancy Cutoff (d)</span>
            <br>

            <input type="text" class="r3dalign-input-mini" value="7">
            <span class="help-inline">Neighborhoods/nucleotide (p)</span>
            <br>

            <input type="text" class="r3dalign-input-mini" value="60">
            <span class="help-inline">Seed alignment bandwidth (&beta;)</span>
            <br>

            <strong>Final clique-finding method:</strong>
            <label class="radio">
              <input type="radio" name="cliqueMethod3" id="optionsRadios1" value="greedy" checked>
              Greedy (Faster)
            </label>
            <label class="radio">
              <input type="radio" name="cliqueMethod3" id="optionsRadios2" value="full">
              Branch and Bound (Exact)
            </label>
          </fieldset>
        </div>
      </div> <!-- row advanced-options -->

    </form>
    </div> <!-- content -->
  </div> <!-- container -->
</div> <!-- wrap -->

<script type="text/javascript" src="<?php echo base_url(); ?>js/homepage.js"></script>
<script>

    $(".pdb1").chosen().change(function(){
        var div_id = "#mol1_info";
        var pdb_id = this.options[this.selectedIndex].text;
        UTIL.load_structure_data(div_id, pdb_id);
    });
    $(".pdb2").chosen().change(function(){
        var div_id = "#mol2_info";
        var pdb_id = this.options[this.selectedIndex].text;
        UTIL.load_structure_data(div_id, pdb_id);
    });

    $("#toggle_advanced").toggle(function(){
        $(this).html('Hide advanced options');
        $('.advanced-options').slideDown();
    }, function(){
        $(this).html('Show advanced options');
        $('.advanced-options').slideUp();
    });

    $("#toggle_iteration2").on('click', function(){
        if ( this.checked ) {
            $("#iteration2").show();
        } else {
            $("#iteration2").hide();
            $("#iteration3").hide();
            $("#toggle_iteration3").prop('checked', false);
        }
    });

    $("#toggle_iteration3").on('click', function(){
        if ( this.checked ) {
            $("#iteration3").show();
        } else {
            $("#iteration3").hide();
        }
    });

</script>