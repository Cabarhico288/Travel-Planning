function displayCredentials(event) {
    const credentialsPreview = document.getElementById('credentials-preview');
    credentialsPreview.innerHTML = '';

    const files = event.target.files;
    for (let i = 0; i < files.length; i++) {
        const file = files[i];

        if (file.type.match('image.*')) {
            const reader = new FileReader();

            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                credentialsPreview.appendChild(img);
            }

            reader.readAsDataURL(file);
        }
    }
}

function displayAvatar(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            document.getElementById('avatar-img').src = e.target.result;
        }

        reader.readAsDataURL(input.files[0]);
    }
}
