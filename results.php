<?php
  function build_results_table () {
    if (! isset ($_POST["contributor"])) {
      # No form search yet
      echo "<P>&nbsp;</P><BLOCKQUOTE><B>Search political contributions from 2001 through the present, using the controls on the left.</B></BLOCKQUOTE>";
    } else {
      # Form search entered
      echo "<PRE>"; print_r ($_POST); echo "</PRE>";
    }
  }
?>