
function checkVal() {
    var id = document.getElementById('studentId').value;
    var email = document.getElementById('email').value;
    var stdName = document.getElementById('fullName').value;
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirm-password').value;

    var formCheck = document.getElementById('form-check').value;
    
    
    if(id == "" ||email == "" || stdName == "" || password == "" || confirmPassword =="") {
        alert("All the field must be filled")
        return false;
    }

    // alert("Registration successfully");
    return true;

}





function validateName() {
    var nameError = document.getElementById('nameError');
    var studentName = document.getElementById('full-name').value;
    
    if (studentName.length == 0) {
        nameError.innerHTML = 'Name is required';
        return false;
    }
    
    if (!studentName.match(/^[A-Za-z]+\s[A-Za-z]+$/)) {
        nameError.innerHTML = 'Write full name';
        return false;
    }
    
    nameError.innerHTML = '<i class="bi bi-check-circle"></i>';
    return true;
}


function validateContact() {
    var contact = document.getElementById('contact-number').value;
    var contactError = document.getElementById('contactError');
    
    if (contact.length == 0) {
        contactError.innerHTML = 'Phone no is required';
        return false;
    }
    if (contact.length != 10) {
        contactError.innerHTML = 'Phone no must be 10 digits';
        return false;
    }
    
    if (!contact.match(/^[98]+[0-9]{8}$/)) {
        contactError.innerHTML = 'Number should be digit and started with 98';
        return false;
    }
    
    
    contactError.innerHTML = '<i class="bi bi-check-circle"></i>';
    return true;
}





function validatePassword() {
    var pass = document.getElementById('password').value;
    var passwordError = document.getElementById('passwordError');
    
    var regex = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d@$!%*?&]{8,}$/;
    
    if (pass.length < 8) {
        passwordError.innerHTML = 'Password must be at least 8 characters';
        return false;
    }
    
    if (!pass.match(regex)) {
        passwordError.innerHTML = 'Password must contain at least one uppercase letter, one number, and one special character';
        return false;
    }
    
    passwordError.innerHTML = '<i class="bi bi-check-circle"></i>';
    return true;
}


function validateConPass() {
    var pass = document.getElementById('password').value;
    var con_pass = document.getElementById('confirm-password').value;
    var conPassError = document.getElementById('conPasswordError');

    if(pass != con_pass) {
        conPassError.innerHTML = 'Password doesnot match'
        return false;
    }

    conPassError.innerHTML = '<i class="bi bi-check-circle"></i>';
    return true;

    
    
}





