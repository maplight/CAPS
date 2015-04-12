//==============================================================================================================
function display_tooltip(event, tip_text, pos_x, pos_y, width) {
  if (window.event) {
    x = window.event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft;
    y = window.event.clientY + document.documentElement.scrollTop + document.body.scrollTop;
  } else {
    x = event.clientX + window.scrollX;
    y = event.clientY + window.scrollY;
  }
  
  document.getElementById('tooltip_text').innerHTML = tip_text;
  tip = document.getElementById('tooltip');
  x = x + pos_x;
  y = y + pos_y;
  tip.style.width = width + 'px';
  tip.style.top = y + 'px';
  tip.style.left = x + 'px';
  tip.style.display = 'block';
}


//==============================================================================================================
function fill_candidate_list(event) {
alert('candidates');
  var keycode = (event.keyCode ? event.keyCode : event.which);
  if (keycode == 40) {
    // Down arrow pressed
    $('#found_candidates option:first-child').attr("selected", "selected");
    $('#found_candidates').focus();
  } else {
    if ($('#search_candidates').val() == '') {
      $('#candidates').hide();
    } else {
      $.ajax({
        type: 'POST',
        url: 'xml/get_candidate_list.php',
        async: false,
        data: {search_text:$('#search_candidates').val()},
        dataType: 'json',
        success: function(data) {fill_candidate_list_return(data);}
      });
    }
  }
}

function fill_candidate_list_return(list_data) {
  if (list_data == '') {
    $('#candidates').hide();
  } else {
    var candidates = '<select size=10 id="found_candidates" style="min-width:190px;">'; 
    for (var i = 0; i < list_data.length; i++) {candidates = candidates + '<option>' + list_data[i].RecipientCandidateNameNormalized + '</option>';}
    candidates = candidates + '</select>';
    $('#candidates').html(candidates);
    // ie8 event handler
    if (document.getElementById('found_candidates').addEventListener) {
      document.getElementById('found_candidates').addEventListener('click', function() {candidate_list_item_clicked();});
      document.getElementById('found_candidates').addEventListener('keydown', function() {candidate_list_item_selected(event);});
    } else {
      document.getElementById('found_candidates').attachEvent('onclick', function() {candidate_list_item_clicked();});
      document.getElementById('found_candidates').attachEvent('onkeydown', function() {candidate_list_item_selected(event);});
    }
    $('#candidates').show();
  }
}

function candidate_list_item_clicked() {
  var candidate_name = $('#found_candidates').val();
  if (candidate_name != '' && candidate_name != null) {
    $('#search_candidates').val(candidate_name);
    $('#match_candidate').val('yes');
    $('#candidates').hide();
  }
}

function candidate_list_item_selected(event) {
  var candidate_name = $('#found_candidates').val();
  var keycode = (event.keyCode ? event.keyCode : event.which);
  if (keycode == 13 && candidate_name != '') {
    $('#match_candidate').val('yes');
    $('#search_candidates').val(candidate_name);
    $('#candidates').hide();
  }
}


//==============================================================================================================
function fill_committee_list(event) {
  var keycode = (event.keyCode ? event.keyCode : event.which);
  if (keycode == 40) {
    // Down arrow pressed
    $('#found_committees option:first-child').attr("selected", "selected");
    $('#found_committees').focus();
  } else {
    if ($('#search_committees').val() == '') {
      $('#committees').hide();
    } else {
      $.ajax({
        type: 'POST',
        url: 'xml/get_committee_list.php',
        async: false,
        data: {search_text:$('#search_committees').val()},
        dataType: 'json',
        success: function(data) {fill_committee_list_return(data);}
      });
    }
  }
}

function fill_committee_list_return(list_data) {
  if (list_data == '') {
    $('#committees').hide();
  } else {
    var committees = '<select size=10 id="found_committees" style="min-width:190px;">'; 
    for (var i = 0; i < list_data.length; i++) {committees = committees + '<option value="' + list_data[i].RecipientCommitteeNameNormalized + '">' + list_data[i].RecipientCommitteeNameNormalized + ' (' + list_data[i].RecipientCommitteeID + ')</option>';}
    committees = committees + '</select>';
    $('#committees').html(committees);
    // ie8 event handler
    if (document.getElementById('found_committees').addEventListener) {
      document.getElementById('found_committees').addEventListener('click', function() {committee_list_item_clicked();});
      document.getElementById('found_committees').addEventListener('keydown', function() {committee_list_item_selected(event);});
    } else {
      document.getElementById('found_committees').attachEvent('onclick', function() {committee_list_item_clicked();});
      document.getElementById('found_committees').attachEvent('onkeydown', function() {committee_list_item_selected(event);});
    }
    $('#committees').show();
  }
}

function committee_list_item_clicked() {
  var committee_name = $('#found_committees').val();
  if (committee_name != '' && committee_name != null) {
    $('#search_committees').val(committee_name);
    $('#match_committee').val('yes');
  }
}

function committee_list_item_selected(event) {
  var committee_name = $('#found_committees').val();
  var keycode = (event.keyCode ? event.keyCode : event.which);
  if (keycode == 13 && committee_name != '') {
    $('#match_committee').val('yes');
    $('#search_committees').val(committee_name);
  }
}

