const preview = (input) => {
  const preview = document.querySelector('#preview-' + input.getAttribute('id'));

  let already_open = false;
  document.querySelectorAll('.btn-tool').forEach((element) => {
    if (element.children[0].getAttribute('class').split(' ')[1] === 'fa-minus' && element.parentElement.parentElement.nextElementSibling.children[0].getAttribute('id') != 'preview-' + input.getAttribute('id')) element.click();
    if (element.children[0].getAttribute('class').split(' ')[1] === 'fa-minus' && element.parentElement.parentElement.nextElementSibling.children[0].getAttribute('id') == 'preview-' + input.getAttribute('id')) already_open = true;
  });
  const oFReader = new FileReader();
  oFReader.readAsDataURL(input.files[0]);
  oFReader.onload = function (oFREvent) {
    preview.src = oFREvent.target.result;
    if (!already_open) preview.parentElement.previousElementSibling.children[1].children[0].click();
    input.nextElementSibling.innerHTML = input.files[0].name;
  };
};
