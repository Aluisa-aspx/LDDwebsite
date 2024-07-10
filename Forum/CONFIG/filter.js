function filterEnabled(event) {
    var inputElement = event.target || event.srcElement;
    var inputValue = inputElement.value;
    var sanitizedValue = inputValue.replace(/[^a-zA-Z0-9 (),.\-!#\/\|]/g, '');
    inputElement.value = sanitizedValue;
}
function filterThread(event) {
    var inputElement = event.target || event.srcElement;
    var inputValue = inputElement.value;
    var sanitizedValue = inputValue.replace(/[^a-zA-Z0-9 (),.\-!#\/\|:;\n~]/g, '');
    inputElement.value = sanitizedValue;
}
