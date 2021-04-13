//1- Ask for a number
var number = parseInt(prompt('Enter number to multiply.'));
//console.log(number);

//2- Check if it's a number
if (!isNaN(number)) {
    //2.1- yes, generate the multiplication table then insert it.
    var ul = "<ul>"
    for (var i = 1; i <= 10; i++) {
        var res = number + " * " + i + " = " + (number * i);
        ul += "<li class='bg-info'>" + res + "</li>"        
    }
    ul += "</ul>";
    document.write(ul);
} else {
    //2.2- no, alert you should enter a number.
    alert('String entered! Please enter a number.');
}