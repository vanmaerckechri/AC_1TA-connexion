window.addEventListener('load', function()
{	
	// -- CREATE DOM ELEMENTS --
	let createDomElem = function (type, attributes)
    {
    	let newElem = document.createElement(type);
    	for (let i = attributes[0].length - 1; i >= 0; i--)
    	{
    		newElem.setAttribute(attributes[0][i], attributes[1][i]);
    	}
    	return newElem;
    }

    // -- CALL A MODAL --
    let loadModal = function(type)
    {
        let modalContainer = createDomElem("div", [["id", "class"], ["modalContainer", "modalContainer"]]);
        let modalBox = createDomElem("div", [["id", "class"], ["modalBox", "modalBox"]]);
        let modalContent = createDomElem("div", [["class"], ["modalContent"]]);
        let leaveModalButton = createDomElem("p", [["class"], ["leaveModalButton"]]);
        let postButton;

        if (type == "classic")
        {
            leaveModalButton.innerText = "X";
            postButton = createDomElem("button", [["id", "class"], ["modalPost", "formButton"]]);
            postButton.innerHTML = "Valider";

            modalBox.appendChild(leaveModalButton);
            modalBox.appendChild(modalContent);
            modalBox.appendChild(postButton);

            leaveModalButton.onclick = function()
            {
                modalContainer.remove();
            }
        }
        // alert!!
        else
        {
            modalBox.className = "closedQuestionAlert";

            postYesButton = createDomElem("button", [["id", "class"], ["postYesButton", "formButton postYesButton"]]);
            postYesButton.innerHTML = "OUI";
            postNoButton = createDomElem("button", [["id", "class"], ["postNoButton", "formButton"]]);
            postNoButton.innerHTML = "NON";
            modalBox.appendChild(modalContent);
            modalBox.appendChild(postYesButton);
            modalBox.appendChild(postNoButton);

            postNoButton.onclick = function()
            {
                modalContainer.remove();
            }
        }

        modalContainer.appendChild(modalBox);
        document.body.appendChild(modalContainer);

        return modalContent;
    }

    if (document.querySelector('#button_create'))
    {
    	// -- VALIDATION WINDOW --
    	let validate = function(form, action, text)
    	{
            textContainer = loadModal("closedQuestionAlert");
    		textContainer.innerHTML = text;

    		let validateYes = function()
    		{
    			modalContainer.remove();
        		form.action = 'admin.php?action='+action;
        		form.submit();
        	}

    		document.getElementById("postYesButton").addEventListener('click', validateYes, false);
    	}
    	// -- CLOSE TOOLS --
    	let closeTools = function(close)
    	{
    		let lastClassroomContainerResult;
    		if (close == "create")
    		{
    			// Hide Create Form
    			if (!createForm.classList.contains('hide'))
    			{
    			    createForm.classList.toggle("hide");
    			    createButton.classList.toggle("toolFocus");	
    			}
    		}
    		if (close == "rename" || close == "create")
    		{
            	// Remove Rename Field if Already Exist
            	if (document.querySelector('.listElementRenameContainer'))
            	{
            		let lastElementRename = document.querySelector('.listElementRenameContainer');
            		lastElementParentRename = lastElementRename.parentElement;
            		lastElementParentRename.removeChild(lastElementRename);
            		lastClassroomContainerResult = lastElementParentRename;
            	}
            	// Remove Submit if Already Exist
            	if (document.querySelector('#submit'))
            	{
            		let lastSubmit = document.querySelector('#submit');
            		let lastSubmitParent = lastSubmit.parentElement;
            		lastSubmitParent.removeChild(lastSubmit);
            	}
            	// Display Static Name
            	let elementNames = document.querySelectorAll('.list .listElementName');
            	for (let i = elementNames.length - 1; i >= 0; i--)
            	{
            		if (elementNames[i].classList.contains('hide'))
            		{
            			elementNames[i].classList.remove('hide');
            			elementNames[i].classList.add('listElementName');
            		}
            	}
            }
            return lastClassroomContainerResult;
    	}
        // -- DELETE SCRIPT --
    	let button_deleteClassrooms = document.querySelector('#delete');
    	let confirmDeleteSelectedClassrooms = function()
    	{
    		closeTools("create");
            let selectedClassrooms = document.querySelector('.list');
            let listElementsContainer = document.querySelectorAll('.listElementDeleteCheck');
            for (let i = listElementsContainer.length - 1; i >= 0; i--)
            {
            	if (listElementsContainer[i].checked == true)
            	{
            		if (detectDeleteElement == "students")
            		{
            			validate(selectedClassrooms, 'deleteStudents&idcr='+deleteElementPartofLink, '<p class="smsAlert"> ATTENTION! Cette opération est irréversible! Êtes-vous sûr de vouloir effacer le(s) élèves(s) sélectionné(s)?</p>');
            		}
            		else
            		{
            			validate(selectedClassrooms, 'deleteClassrooms', '<p class="smsAlert"> ATTENTION! Cette opération est irréversible! Tous les élèves appartenant à la classe seront eux aussi effacés! Êtes-vous sûr de vouloir effacer la/les classe(s) sélectionnée(s)?</p>');
            		}
       				return;
            	}
            }
            let smsContainer = document.querySelector('.sms');
            smsContainer.innerHTML = '<span class="smsAlert">Vous n\'avez rien Sélectionné!</span>';
    	}
        button_deleteClassrooms.addEventListener('click', confirmDeleteSelectedClassrooms, false);

    	// -- CREATE SCRIPT --
        let createButton = document.querySelector('#button_create');
        let createForm = document.querySelector('.form_create');
        let openCreateTool = function()
        {            	
        	closeTools("rename");
            createForm.classList.toggle("hide");
            createButton.classList.toggle("toolFocus");
            // autofocus
            if (createForm.classList != "hide")
            {
                let inputNewStudentNickname = document.querySelector('.newStudentNick');
                inputNewStudentNickname.focus();
            }
        }

        createButton.addEventListener('click', openCreateTool, false);

        // -- RENAME SCRIPT --
        let button_rename = document.querySelectorAll('.buttonRename');
        let openRenameTool = function(event)
        {
        	let lastClassroomContainer = closeTools("create");
        	// Create Rename Field
        	let buttonRename = event.target;
        	let classroomContainer = buttonRename.parentElement;
        	if (lastClassroomContainer != classroomContainer)
        	{
            	let classroomName = classroomContainer.querySelector('.listElementName').innerText;
            	let classroomId = classroomContainer.querySelector(".listElementDeleteCheck").value;
            		// Name Field
            	// Name Field
    			let listElementRenameContainer = createDomElem("div", [["class"], ["listElementRenameContainer"]]);
        		let listElementRename = createDomElem("input", [["type", "name", "value", "class"], ["text", "rename", classroomName, "listElementRename"]]);
    			listElementRenameContainer.appendChild(listElementRename);
    			if (detectDeleteElement == "students")
    			{
    				// Password Field
    				let password = classroomContainer.querySelector('.pwd').innerText;
        			let listElementPassword = createDomElem("input", [["type", "name", "value", "class"], ["text", "newPassword", password, "listElementRename"]]);
    				listElementRenameContainer.appendChild(listElementPassword);
    			}
            		// classroomId Field
        		let listElementId = createDomElem("input", [["type", "name", "value"], ["hidden", "idElem", classroomId]]);
    			listElementRenameContainer.appendChild(listElementId);
    			classroomContainer.insertBefore(listElementRenameContainer, buttonRename);
    				// submit
        		let submit = createDomElem("input", [["type", "value", "id", "class"], ["submit", "Enregistrer", "submit", "formButton"]]);
    			classroomContainer.appendChild(submit);

    			let elementName = classroomContainer.querySelector('.listElementName');
    			elementName.classList.toggle("hide");
    			document.querySelector('.listElementRename').focus();
    		}
        }

        for (let i = button_rename.length - 1; i >= 0; i--)
        {
            button_rename[i].addEventListener('click', openRenameTool, false);
        }
    }

    // -- PROFIL INFOS --
    if (document.getElementById("changeMail"))
    {
        let changeMail = document.getElementById("changeMail");
        let changePassword = document.getElementById("changePassword");
        let deleteAccount = document.getElementById("deleteAccount");

        let launchChangePasswordManagement = function()
        {
            let modalContent = loadModal("classic");
            let modalPost = document.getElementById("modalPost");
            let form = createDomElem("form", [["method"], ["post"]]);
            modalContent.appendChild(form);
            form.innerHTML += "<label>Mot de Passe Actuel<input id='oldPwd' class='newPwdInput' type='password' name='oldPwd'><p class='smsAlert wrongInput'></p></label>";
            form.innerHTML += "<label>Nouveau Mot de Passe<input id='newPassword' class='newPwdInput' type='password' name='newPassword'><p class='smsAlert wrongInput'></p></label>";
            form.innerHTML += "<label>Répeter le Nouveau Mot de Passe<input id='newPassword2' class='newPwdInput' type='password' name='newPassword2'><p class='smsAlert wrongInput'></p></label>";
            let oldPwd = document.getElementById("oldPwd");
            let newPwd = document.getElementById("newPassword");
            let newPwd2 = document.getElementById("newPassword2");

            modalPost.onclick = function()
            {
                if (oldPwd.value.length >= 5 && oldPwd.value.length <= 30 && newPwd.value.length >= 5 && newPwd.value.length <= 30 && newPwd.value === newPwd2.value)
                {
                    form.action = 'admin.php?action=changePwd';
                    form.submit();
                }
                else
                {
                    let inputs = document.querySelectorAll(".newPwdInput");
                    let alertInfo = document.querySelectorAll(".wrongInput");
                    for (let i = inputs.length - 1; i >= 0; i--)
                    {
                        alertInfo[i].innerText = "";
                        if (inputs[i].value.length < 5 || inputs[i].value.length > 30)
                        {
                            alertInfo[i].innerText = "Ce champ doit contenir de 5 à 30 caractères!";
                        }
                    }

                    if (newPwd.value !== newPwd2.value)
                    {
                        alertInfo[2].innerText += "Vous avez mal répété votre nouveau mot de passe!";
                    }
                }
            }
        }

        let launchDeleteAccountManagement = function()
        {
            let modalContent = loadModal("closedQuestionAlert");
            modalContent.innerHTML += "<p class='smsAlert'>Cette action supprimera votre compte ainsi que celui de tous vos élèves et ce de façon irréversible!</p>";

            // replace old yes button by a simple link with a "get" value
            let oldPostYesButton = document.getElementById("postYesButton");
            let newPostYesButton = createDomElem("a", [["id", "class", "href"], ["postYesButton", "formButton postYesButton", "admin.php?action=deleteAccount"]]);
            newPostYesButton.innerText = "OUI"
            oldPostYesButton.setAttribute("id", "");
            oldPostYesButton.parentNode.insertBefore(newPostYesButton, oldPostYesButton);
            oldPostYesButton.remove();
        }

        changePassword.addEventListener('click', launchChangePasswordManagement, false);
        deleteAccount.addEventListener('click', launchDeleteAccountManagement, false);

        // init
        if (adAccountState == "changeMailWaitingCode")
        {
            let modalContent = loadModal("classic");
            let modalPost = document.getElementById("modalPost");
            let form = createDomElem("form", [["method"], ["post"]]);
            modalContent.appendChild(form);
            form.innerHTML += "<label>Code de Validation<input id='newMailCode' class='newPwdInput' type='password' name='newMailCode'><p class='smsAlert wrongInput'></p></label>";
            form.innerHTML += "<label>Mot de Passe<input id='pwd' class='newPwdInput' type='password' name='pwd'><p class='smsAlert wrongInput'></p></label>";
            form.innerHTML += "<label>Nouvelle Adresse Mail<input id='newMail' class='newPwdInput' type='email' name='newMail'><p class='smsAlert wrongInput'></p></label>";
            form.innerHTML += "<label>Répeter la Nouvelle Adresse Mail<input id='newMail2' class='newPwdInput' type='email' name='newMail2'><p class='smsAlert wrongInput'></p></label>";
            let newMailCode = document.getElementById("newMailCode");
            let pwd = document.getElementById("pwd");
            let newMail = document.getElementById("newMail");
            let newMail2 = document.getElementById("newMail2");

            let inputs = document.querySelectorAll(".newPwdInput");
            let smsInfo = document.querySelector(".smsInfo");
            if (smsInfo)
            {
                smsInfo.innerHTML += "<br><br>";
                form.appendChild(smsInfo);
            }
            modalPost.onclick = function()
            {
                if (newMailCode.value.length == 8 && newMail.value.length >= 5 && newMail.value.length <= 78 && newMail.value === newMail2.value && pwd.value.length >= 5 && pwd.value.length <= 30)
                {
                    form.action = 'admin.php?action=changeMail';
                    form.submit();
                }
                else
                {
                    let alertInfo = document.querySelectorAll(".wrongInput");
                    for (let i = inputs.length - 1; i >= 0; i--)
                    {
                        alertInfo[i].innerText = "";
                        if (inputs[i].value.length < 5 || inputs[i].value.length > 78)
                        {
                            if (i != 0)
                            {
                                alertInfo[i].innerText = "Ce champ doit contenir de 5 à 78 caractères!";
                            }
                        }
                    }

                    if (newMailCode.value.length != 8)  
                    {
                        alertInfo[0].innerText = "Ce code doit contenir 8 caractères (il vous a été envoyé par mail)!"; 
                    }

                    if (pwd.value.length < 5 || pwd.value.length > 30)  
                    {
                        alertInfo[1].innerText = "Ce champ doit contenir de 5 à 30 caractères!"; 
                    }

                    if (newMail.value !== newMail2.value)
                    {
                        alertInfo[2].innerText += "Vous avez mal répété la nouvelle adresse mail!";
                    }
                    smsInfo.remove();
                }
            }
        }
    }
}, false);