<div id="wrap">
  <div class="container r3dalign-results">

    <div class="content">

      <div class="row">

        <div class="span6"> <!-- left panel -->

          <select class="pdb1" data-placeholder="Choose first PDB id">
            <option></option>
            <?php foreach ($pdbs as $pdb): ?>
              <option value="<?=$pdb?>"><?=$pdb?></option>
            <?php endforeach; ?>
          </select>

          <br><br>

          <select class="pdb2" data-placeholder="Choose second PDB id">
            <option></option>
            <?php foreach ($pdbs as $pdb): ?>
              <option value="<?=$pdb?>"><?=$pdb?></option>
            <?php endforeach; ?>
          </select>


        </div> <!-- span6, left panel -->

      </div> <!-- row -->

    </div> <!-- content -->

  </div> <!-- container -->
</div> <!-- wrap -->

<script>
     $(".pdb1").chosen();
     $(".pdb2").chosen();

</script>