
    <?php if ( isset($verbose_footer) and $verbose_footer ): ?>
      <footer>
        <p>
          <a href="http://rna.bgsu.edu">BGSU RNA group</a>, <?php echo date("Y"); ?>
          <br>
          Page generated in {elapsed_time} s
        </p>
      </footer>
    <?php endif; ?>

    <!-- Google Analytics Tracking -->
    <script type="text/javascript">

      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-19716391-1']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();

    </script>
    <!-- Google Analytics Tracking -->

</body>
</html>