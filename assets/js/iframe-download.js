function download(file, text) {
  var element = document.createElement('a');
  element.setAttribute('href', file.parentElement.parentElement.nextElementSibling.children[0].getAttribute('src'));
  element.setAttribute('download', text);

  document.body.appendChild(element);

  element.click();

  document.body.removeChild(element);
}
