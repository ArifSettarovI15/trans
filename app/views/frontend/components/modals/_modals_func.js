function ShowThx(response,ajax_config,textStatus,jqXHR) {
  if (response.status) {
    openInlineModal(response.result);
    resetForm(ajax_config.options.form_obj)
  }
}

function InlineSearch(string,find_string) {
  var arr = find_string.trim().split(' ');
  var ok=true;
  arr.forEach(function(vv) {
    if (string.indexOf(vv) > -1) {

    }
    else {
      ok=false;
    }
  });

  return ok;
}
