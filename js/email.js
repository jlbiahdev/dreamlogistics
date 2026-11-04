const emailjsConfig = {
    service_id: 'service_3nge4zh',
    template_id:'template_9uf9nvn',
    public_key:'HHi-YtFsGz1QmtvVT'
};

function sendEmail() {
    console.log('sendEmail');

    let params = {
        to_name: 'Support DreamLogistics',
        from_name: document.getElementById('name').value,
        from_email: document.getElementById('email').value,
        from_phonenumber: document.getElementById('phonenumber').value,
        message: document.getElementById('message').value,
        subject: `[${document.getElementById('academie').value}] : ` + document.getElementById('subject').value,
        reply_to: "luc@luc.com",
    }

    pushEmail(params);
}

function sendComment() {
    console.log('sendComment');

    let params = {
        to_name: 'Support DreamLogistics',
        from_name: document.getElementById('name').value,
        from_email: document.getElementById('email').value,
        from_phonenumber: document.getElementById('phonenumber').value,
        message: document.getElementById('message').value,
        subject: 'Nouveau Commentaire Chez DreamLogistics',
        // reply_to: "luc@luc.com",
    }

    pushEmail(params);
}

function pushEmail(params) {
    
    var data = {
    service_id: 'service_3nge4zh',
    template_id: 'template_9uf9nvn',
    user_id: 'HHi-YtFsGz1QmtvVT',
    template_params: params
};
 
$.ajax('https://api.emailjs.com/api/v1.0/email/send', {
    type: 'POST',
    data: JSON.stringify(data),
    contentType: 'application/json'
}).done(function() {
    displaySuccess('Votre mail a été envoyé!');
    resetControls();
}).fail(function(error) {
    console.log('Oops... ' + JSON.stringify(error));
    displayError(`Un problème est survenu lors de l'envoi de votre mail`)
});
}

function displayMessage(message, classnames) {
    const box = document.getElementById('message-box');
    setTimeout(() => {
        setTimeout(() => {
            box.style.display = 'none';
            
            console.log('box.style.display = none');
        }, 10000);

        const paragraf = document.getElementById('paragraf');

        paragraf.innerText = message;
        box.style.display = 'block';
        box.className += classnames;
        
        console.log('box.style.display = block');
    }, 1000);

}

function displaySuccess(message) {
    displayMessage(message, 'message-box-success fa-times-success msg-title-success');
}

function displayError(message) {
    displayMessage(message, 'message-box-error fa-times-error msg-title-error');
}

function resetControls() {
    document.getElementById('name').value = '';
    document.getElementById('email').value = '';
    document.getElementById('phonenumber').value = '';
    document.getElementById('message').value = '';

    let academie = document.getElementById('academie');
    let subject = document.getElementById('subject')
    
    if (academie !== undefined && academie !== null) academie.selectedindex = 0;
    if (subject !== undefined && subject !== null) subject.value = '';
}
