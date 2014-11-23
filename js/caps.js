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
    var candidates = '<select size=10 id="found_candidates" onclick="list_item_clicked(this.value);" onkeydown="list_item_selected(event, this.value);">'; 
    list_data.forEach(function(item) {candidates = candidates + '<option>' + item.RecipientCandidateNameNormalized + '</option>';});
    candidates = candidates + '</select>';
    $('#candidates').html(candidates);
    $('#candidates').show();
  }
}

function list_item_clicked(candidate_name) {
  $('#search_candidates').val(candidate_name);
  $('#match_candidate').val('yes');
  $('#qs_candidate_btn').trigger('click');
}

function list_item_selected(event, candidate_name) {
  var keycode = (event.keyCode ? event.keyCode : event.which);
  if (keycode == 13) {
    $('#match_candidate').val('yes');
    $('#search_candidates').val(candidate_name);
  }
}


//==============================================================================================================




function filter_candidates_list() {
  $('#candidate_list').empty();

  $.each(candidates, function(key, value) {
    if (value != '') {
      if (value == 'Select candidate') {
        $('#candidate_list')
          .append($('<option></option>')
          .attr('value', value)
          .text(value)); 
      } else {
        if (value.indexOf($('#search_candidates').val().toUpperCase()) != -1) {
          $('#candidate_list')
            .append($('<option></option>')
            .attr('value', value)
            .text(value));
        }
      }
    }
  });
}


//==============================================================================================================
function filter_propositions_list() {
  $('#propositions_list').empty();

  $.each(propositions, function(key, value) {
    if (value.length == 2) {
      if (value[0].substring(0, 3) == 'ALL') {
        $('#propositions_list')
          .append($('<option></option>')
          .attr('value', value[0])
          .text(value[1]));
      } else {
        if (value[0].indexOf($('#search_propositions').val().toUpperCase()) != -1) {
          $('#propositions_list')
            .append($('<option></option>')
            .attr('value', value[0])
            .text(value[1]));
        }
      }
    } 
  });
}
