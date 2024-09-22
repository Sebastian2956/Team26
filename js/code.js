// TODO: this url will have to be updated
// testing locally: http://localhost/Team26/LAMPAPI
// production : http://team26cm.seb.christmas/Team26/LAMPAPI
const urlBase = 'http://localhost/Team26/LAMPAPI';
const extension = 'php';

var userId = 0;
let firstName = "";
let lastName = "";

function showContactDetails(contact) {

	// retrieve contact-details
    let contactDetails = document.getElementById("contact-details");

    // clear previous content
    contactDetails.innerHTML = '';

    // create elements for each field
    let firstNameDetail = document.createElement("p");
    firstNameDetail.innerHTML = `<strong>First Name:</strong> ${contact.FirstName}`;

    let lastNameDetail = document.createElement("p");
    lastNameDetail.innerHTML = `<strong>Last Name:</strong> ${contact.LastName}`;

    let emailDetail = document.createElement("p");
    emailDetail.innerHTML = `<strong>Email:</strong> ${contact.Email}`;

    let phoneDetail = document.createElement("p");
    phoneDetail.innerHTML = `<strong>Phone:</strong> ${contact.Phone}`;

    // append elements to contactDetails
    contactDetails.appendChild(firstNameDetail);
    contactDetails.appendChild(lastNameDetail);
    contactDetails.appendChild(emailDetail);
    contactDetails.appendChild(phoneDetail);
}

function doPopulate()
{
    //this retrieves the session's cookies (userId won't be 0)
    readCookie(); 


    let tmp = {userId:userId};
    let jsonPayload = JSON.stringify(tmp);

    let xhr = new XMLHttpRequest();

    //finds the php file to run
    xhr.open("POST", urlBase + "/PopulateContactList.php", true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    // when the request loads this will parse the reponse
    xhr.onload = function() {
		if (xhr.status === 200) {
			let contacts = JSON.parse(xhr.responseText);
			console.log(contacts);

            // retrieve contact-list
            let contactList = document.getElementById("contact-list");

            // clear previous content
            contactList.innerHTML = '';

            // loop through each contact and add to list
            contacts.forEach(contact => {
                let li = document.createElement("li");

                // create li fullname string
                li.textContent = `${contact.FirstName} ${contact.LastName}`;

                // show contact details onClick of contact name
                li.onclick = function() {
                    showContactDetails(contact);
                };

                // append item to list
                contactList.appendChild(li);
            });
        } else {
            console.error('Request failed. Status: ' + xhr.status);
        }
    };

    xhr.send(jsonPayload);
}

function doSearch()
{
    //this retrieves the session's cookies (userId won't be 0)
    readCookie();

    //The id tag will have to be searchElement if you want this search to work
    let searchElement = document.getElementById("searchElement").value;

    let tmp = {userID:userId,searchElement:searchElement};
    let jsonPayload = JSON.stringify(tmp);

    let xhr = new XMLHttpRequest();

    //finds the php file to run
    xhr.open("POST", urlBase + "/SearchContacts.php", true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    // when the request loads this will parse the reponse
    xhr.onload = function() {
	if (xhr.status === 200) {
	    let response = JSON.parse(xhr.responseText);
	    console.log(response);
	} else {
	    console.error('Request failed. Status: ' + xhr.status);
	}
    }
    xhr.send(jsonPayload);
}

function doLogin()
{
	userId = 0;
	firstName = "";
	lastName = "";

  //gets the username and password from the input tag on the .html that calls it
	let login = document.getElementById("loginUsername").value;
	let password = document.getElementById("loginPassword").value;
//	var hash = md5( password );

	document.getElementById("loginResult").innerHTML = "";

  //creates javascript struct
  //this is a struct
	let tmp = {login:login,password:password};
  //	var tmp = {login:login,password:hash};
  // converts the struct to a json blob
	let jsonPayload = JSON.stringify( tmp );

  //this is just a window alert
  window.alert("This: " + jsonPayload);

  //this picks the php file
	let url = urlBase + '/Login.' + extension;

  //this is what actually creates the post request (AJAX)
	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
    //this defines the reponse processing functions
		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{

				let jsonObject = JSON.parse( xhr.responseText );
				userId = jsonObject.id;

				if( userId < 1 )
				{
					document.getElementById("loginResult").innerHTML = "User/Password combination incorrect";
					return;
				}

				firstName = jsonObject.FirstName;
				lastName = jsonObject.LastName;

				console.log(firstName);
				console.log(lastName);

				window.alert("Hey " + firstName + " " + lastName + " " + jsonObject.id);

				saveCookie();

				window.location.href = "dashboard.html";
			}
		};
    //This sends the message to the url (Login.php)
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("loginResult").innerHTML = err.message;
		window.alert(err.message);
	}


    // TODO: Testing
    // window.userId = 255;
}

// work in progress
function doRegister()
{
	console.log("doRegister working");
	let firstName = document.getElementById("registerFirstName").value;
	let lastName = document.getElementById("registerLastName").value;
	let login = document.getElementById("registerUsername").value;
	let password = document.getElementById("registerPassword").value;
//	var hash = md5( password );

	document.getElementById("registerResult").innerHTML = "";

	let tmp = {FirstName:firstName,LastName:lastName,login:login,password:password};

//	var tmp = {login:login,password:hash};
	let jsonPayload = JSON.stringify( tmp );

	let url = urlBase + '/AddUser.' + extension;

	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				let jsonObject = JSON.parse( xhr.responseText );
				userId = jsonObject.id;

				if( jsonObject.error !== "" )
				{
					document.getElementById("registerResult").innerHTML = jsonObject.error;
					return;
				}

				window.alert("Successful Registeration");

				saveCookie();

				window.location.href = "index.html";
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("registerResult").innerHTML = err.message;
		window.alert(err.message);
	}

}

function saveCookie()
{
	let minutes = 20;
	let date = new Date();
	date.setTime(date.getTime()+(minutes*60*1000));
	document.cookie = "firstName=" + firstName + ",lastName=" + lastName + ",userId=" + userId + ";expires=" + date.toGMTString();
}

function readCookie()
{
	userId = -1;
	let data = document.cookie;
	console.log("Cookies:", data);

	let splits = data.split(",");
	for(var i = 0; i < splits.length; i++)
	{
	    let thisOne = splits[i].trim();
		let tokens = thisOne.split("=");
		if( tokens[0] == "firstName" )
		{
			firstName = tokens[1];
		}
		else if( tokens[0] == "lastName" )
		{
			lastName = tokens[1];
		}
		else if( tokens[0] == "userId" )
		{
			userId = parseInt( tokens[1].trim() );
		}
	}

	if( userId < 0 )
	{
		window.location.href = "index.html";
	}
	else
	{
	console.log("User ID found:", userId);
	return userId;
}

}

function doLogout()
{
	userId = 0;
	firstName = "";
	lastName = "";
	document.cookie = "firstName= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
	window.location.href = "index.html";
}

function doDelete()
{
	readCookie();

	let contactFirst = document.getElementById("contactFirstName").value;
	let contactLast = document.getElementById("contactLastName").value;
	let tmp = {contactFirstName: contactFirst, contactLastName: contactLast };
    let jsonPayload = JSON.stringify(tmp);

	let xhr = new XMLHttpRequest();

	//finds the php file to run
	let url = urlBase + '/DeleteContact.' + extension;
    xhr.open("POST", url + "/DeleteContact.php", true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

	// Handle the server's response
    xhr.onload = function() {
        if (xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);
            console.log(response);

            if (response.error === "") {
                console.log("Contact deleted successfully.");
            } else {
                console.error("Error deleting contact: " + response.error);
            }
        } else {
            console.error("Request failed. Status: " + xhr.status);
        }
    };

	xhr.send(jsonPayload);
}

// not working; still need to read in userId from cookie
function doAddContact()
{
	console.log("doAddContact() working")

	let userId = readCookie();
	console.log("userId from cookie:", userId);
	
	let firstName = document.getElementById("contactFirstName").value;
	let lastName = document.getElementById("contactLastName").value;
	let email = document.getElementById("contactEmail").value;
	let phone = document.getElementById("contactPhone").value;

	let tmp = {FirstName:firstName,LastName:lastName,Email:email,Phone:phone, userId:userId};
	console.log("Payload: ", tmp); 
	let jsonPayload = JSON.stringify( tmp );

	let url = urlBase + '/AddContact.' + extension;

	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	
	xhr.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			let jsonObject = JSON.parse( xhr.responseText );
			console.log(jsonObject);

			if( jsonObject.error !== "" ) {
				return;
			}

			window.alert("Contact Added");
		} else {
            console.error('Request failed. Status: ' + xhr.status);
		}
	};
	xhr.send(jsonPayload);
}