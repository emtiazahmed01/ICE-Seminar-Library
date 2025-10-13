// Profile Picture Preview
    document.getElementById("profile").addEventListener("change", function(event) {
      let reader = new FileReader();
      reader.onload = function(){
        document.getElementById("profilePreview").innerHTML = `<img src="${reader.result}" width="100" alt="Profile Preview">`;
      };
      reader.readAsDataURL(event.target.files[0]);
    });

    // Signature Preview
    document.getElementById("signature").addEventListener("change", function(event) {
      let reader = new FileReader();
      reader.onload = function(){
        document.getElementById("signaturePreview").innerHTML = `<img src="${reader.result}" width="200" alt="Signature Preview">`;
      };
      reader.readAsDataURL(event.target.files[0]);
    });
