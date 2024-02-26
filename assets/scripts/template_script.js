document.addEventListener('DOMContentLoaded', function () {
    var testMsg = document.getElementById('testmsg');
    var nameInput = document.getElementById('create_template_name');
    var subjectInput = document.getElementById('create_template_subject');

    function updateBorderAndContent() {
        if (nameInput.value.trim() === '' || subjectInput.value.trim() === '') {
            testMsg.classList.add('border-red');
            testMsg.classList.remove('border-green');

            testMsg.innerHTML = '<a href="javascript:void(0)" id="show-div-link" class="leading-6 font-semibold w-full h-full py-4 px-4 inline-flex items-center">\n' +
                '<div class="grid grid-cols-5 w-full">' +
                '<div class="col-span-1 self-center"><svg viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="h-6 w-6 iconify iconify--twemoji" preserveAspectRatio="xMidYMid meet" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path fill="#FFCC4D" d="M2.653 35C.811 35-.001 33.662.847 32.027L16.456 1.972c.849-1.635 2.238-1.635 3.087 0l15.609 30.056c.85 1.634.037 2.972-1.805 2.972H2.653z"></path><path fill="#231F20" d="M15.583 28.953a2.421 2.421 0 0 1 2.419-2.418a2.421 2.421 0 0 1 2.418 2.418a2.422 2.422 0 0 1-2.418 2.419a2.422 2.422 0 0 1-2.419-2.419zm.186-18.293c0-1.302.961-2.108 2.232-2.108c1.241 0 2.233.837 2.233 2.108v11.938c0 1.271-.992 2.108-2.233 2.108c-1.271 0-2.232-.807-2.232-2.108V10.66z"></path></g></svg></div>' +
                '<div class="col-span-3 text-center"><h4 class="text-lg">Editer l\'objet\n !</h4><p class="font-normal text-xs">Des champs sont manquants</p></div>' +
                '<div class="col-span-1 place-self-end self-center"><svg class="h-6 w-6" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <rect x="225.031" y="19.866" transform="matrix(-0.7071 -0.7071 0.7071 -0.7071 308.8203 596.154)" style="fill:#FFBA57;" width="105.693" height="428.505"></rect> <polygon style="fill:#E6E6E6;" points="30.211,450.505 61.496,481.789 163.75,422.985 89.015,348.25 "></polygon> <rect x="18.733" y="460.863" transform="matrix(-0.7071 -0.7071 0.7071 -0.7071 -282.116 840.9244)" style="fill:#B3B3B3;" width="28.741" height="36.054"></rect> <rect x="314.691" y="91.403" transform="matrix(-0.7071 -0.7071 0.7071 -0.7071 577.7177 454.059)" style="fill:#E6E6E6;" width="136.413" height="31.954"></rect> <rect x="434.531" y="14.781" transform="matrix(-0.7071 -0.7071 0.7071 -0.7071 742.4036 416.5487)" style="fill:#E8655A;" width="45.882" height="79.473"></rect> <g> <path style="fill:#603813;" d="M211.867,167.522c-3.983,3.983-3.983,10.441,0,14.425c1.992,1.992,4.602,2.987,7.212,2.987 c2.61,0,5.22-0.995,7.212-2.987l97.071-97.07l7.301,7.301L81.803,341.038c0,0-0.011,0.017-0.034,0.051 c-0.611,0.617-1.154,1.308-1.596,2.077L21.37,445.42c-1.879,3.268-1.773,7.232,0.109,10.351L2.987,474.264 C1.075,476.176,0,478.771,0,481.476c0,2.705,1.075,5.3,2.987,7.212l20.326,20.325c1.992,1.992,4.602,2.987,7.212,2.987 s5.22-0.996,7.212-2.987l18.498-18.498c1.603,0.967,3.424,1.475,5.264,1.475c1.741,0,3.494-0.445,5.081-1.358l102.255-58.805 c0.77-0.443,1.461-0.987,2.079-1.598c0.03-0.019,0.049-0.032,0.049-0.032l302.999-302.999c3.983-3.983,3.983-10.441,0-14.425 L460.028,98.84l48.985-48.985c3.983-3.983,3.983-10.441,0-14.424L476.569,2.987C474.657,1.075,472.063,0,469.357,0 c-2.706,0-5.298,1.075-7.212,2.987L413.16,51.972l-13.934-13.934c-3.982-3.982-10.438-3.983-14.425,0l-17.119,17.12l-14.512-14.512 c-1.913-1.912-4.507-2.987-7.212-2.987s-5.298,1.075-7.212,2.987L211.867,167.522z M392.014,59.674l60.312,60.312l-9.909,9.909 l-60.312-60.312L392.014,59.674z M345.956,62.281l82.037,82.038l-8.171,8.171l-82.037-82.037L345.956,62.281z M405.399,166.913 L163.751,408.561l-60.312-60.312l241.648-241.648L405.399,166.913z M30.525,487.377l-5.901-5.901l11.067-11.068l5.901,5.901 L30.525,487.377z M147.124,420.781l-83.939,48.272l-20.237-20.236l48.271-83.939L147.124,420.781z M427.584,66.396l41.773-41.773 l18.019,18.02l-41.773,41.773L427.584,66.396z"></path> <path style="fill:#603813;" d="M157.003,385.299c1.992,1.992,4.602,2.987,7.212,2.987c2.61,0,5.22-0.995,7.212-2.987l79.871-79.871 c3.983-3.983,3.983-10.441,0-14.425c-3.984-3.983-10.44-3.983-14.425,0l-79.871,79.871 C153.019,374.858,153.019,381.317,157.003,385.299z"></path> <path style="fill:#603813;" d="M266.402,286.098c2.61,0,5.22-0.995,7.211-2.986l2.674-2.674c3.983-3.983,3.984-10.441,0.001-14.424 c-3.981-3.983-10.44-3.984-14.424-0.001l-2.674,2.674c-3.983,3.983-3.984,10.441-0.001,14.424 C261.181,285.101,263.792,286.098,266.402,286.098z"></path> </g> </g></svg></div>' +
                '</div>' +
                '</a>';

        } else {
            testMsg.classList.add('border-green');
            testMsg.classList.remove('border-red');

            testMsg.innerHTML = '<a href="#" id="show-div-link" class="leading-6 font-semibold w-full h-full py-4 px-4 inline-flex items-center">\n' +
                '<div class="grid grid-cols-5 w-full">' +
                '<div class="col-span-1 self-center"><svg fill="#fff" viewBox="0 0 24 24" id="check-mark-circle-2" data-name="Flat Line" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 icon flat-line" stroke="#fff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><polyline id="primary" points="21 5 12 14 8 10" style="fill: none; stroke: #fff; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></polyline><path id="primary-2" data-name="primary" d="M20.94,11A8.26,8.26,0,0,1,21,12a9,9,0,1,1-9-9,8.83,8.83,0,0,1,4,1" style="fill: none; stroke: #fff; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></path></g></svg></div>' +
                '<div class="col-span-3 text-center"><h4 class="text-lg">Tout est bon !</h4><p class="font-normal text-xs">Tous vos champs sont remplis !</p></div>' +
                '<div class="col-span-1 place-self-end self-center"><svg class="h-6 w-6" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <rect x="225.031" y="19.866" transform="matrix(-0.7071 -0.7071 0.7071 -0.7071 308.8203 596.154)" style="fill:#FFBA57;" width="105.693" height="428.505"></rect> <polygon style="fill:#E6E6E6;" points="30.211,450.505 61.496,481.789 163.75,422.985 89.015,348.25 "></polygon> <rect x="18.733" y="460.863" transform="matrix(-0.7071 -0.7071 0.7071 -0.7071 -282.116 840.9244)" style="fill:#B3B3B3;" width="28.741" height="36.054"></rect> <rect x="314.691" y="91.403" transform="matrix(-0.7071 -0.7071 0.7071 -0.7071 577.7177 454.059)" style="fill:#E6E6E6;" width="136.413" height="31.954"></rect> <rect x="434.531" y="14.781" transform="matrix(-0.7071 -0.7071 0.7071 -0.7071 742.4036 416.5487)" style="fill:#E8655A;" width="45.882" height="79.473"></rect> <g> <path style="fill:#603813;" d="M211.867,167.522c-3.983,3.983-3.983,10.441,0,14.425c1.992,1.992,4.602,2.987,7.212,2.987 c2.61,0,5.22-0.995,7.212-2.987l97.071-97.07l7.301,7.301L81.803,341.038c0,0-0.011,0.017-0.034,0.051 c-0.611,0.617-1.154,1.308-1.596,2.077L21.37,445.42c-1.879,3.268-1.773,7.232,0.109,10.351L2.987,474.264 C1.075,476.176,0,478.771,0,481.476c0,2.705,1.075,5.3,2.987,7.212l20.326,20.325c1.992,1.992,4.602,2.987,7.212,2.987 s5.22-0.996,7.212-2.987l18.498-18.498c1.603,0.967,3.424,1.475,5.264,1.475c1.741,0,3.494-0.445,5.081-1.358l102.255-58.805 c0.77-0.443,1.461-0.987,2.079-1.598c0.03-0.019,0.049-0.032,0.049-0.032l302.999-302.999c3.983-3.983,3.983-10.441,0-14.425 L460.028,98.84l48.985-48.985c3.983-3.983,3.983-10.441,0-14.424L476.569,2.987C474.657,1.075,472.063,0,469.357,0 c-2.706,0-5.298,1.075-7.212,2.987L413.16,51.972l-13.934-13.934c-3.982-3.982-10.438-3.983-14.425,0l-17.119,17.12l-14.512-14.512 c-1.913-1.912-4.507-2.987-7.212-2.987s-5.298,1.075-7.212,2.987L211.867,167.522z M392.014,59.674l60.312,60.312l-9.909,9.909 l-60.312-60.312L392.014,59.674z M345.956,62.281l82.037,82.038l-8.171,8.171l-82.037-82.037L345.956,62.281z M405.399,166.913 L163.751,408.561l-60.312-60.312l241.648-241.648L405.399,166.913z M30.525,487.377l-5.901-5.901l11.067-11.068l5.901,5.901 L30.525,487.377z M147.124,420.781l-83.939,48.272l-20.237-20.236l48.271-83.939L147.124,420.781z M427.584,66.396l41.773-41.773 l18.019,18.02l-41.773,41.773L427.584,66.396z"></path> <path style="fill:#603813;" d="M157.003,385.299c1.992,1.992,4.602,2.987,7.212,2.987c2.61,0,5.22-0.995,7.212-2.987l79.871-79.871 c3.983-3.983,3.983-10.441,0-14.425c-3.984-3.983-10.44-3.983-14.425,0l-79.871,79.871 C153.019,374.858,153.019,381.317,157.003,385.299z"></path> <path style="fill:#603813;" d="M266.402,286.098c2.61,0,5.22-0.995,7.211-2.986l2.674-2.674c3.983-3.983,3.984-10.441,0.001-14.424 c-3.981-3.983-10.44-3.984-14.424-0.001l-2.674,2.674c-3.983,3.983-3.984,10.441-0.001,14.424 C261.181,285.101,263.792,286.098,266.402,286.098z"></path> </g> </g></svg></div>' +
                '</div>' +
                '</a>';
        }
    }

    nameInput.addEventListener('input', updateBorderAndContent);
    subjectInput.addEventListener('input', updateBorderAndContent);

    updateBorderAndContent();
});

document.addEventListener('DOMContentLoaded', function () {
    var nameInput = document.getElementById('create_template_name');
    var templateNameDiv = document.getElementById('template_name');

    templateNameDiv.innerHTML = '<h3 class="leading-6 font-semibold text-lg">Nom du template : ' + '<span class="underline font-normal">' + nameInput.value +'</span></h3>';

    nameInput.addEventListener('input', function () {
        templateNameDiv.innerHTML = '<h3 class="leading-6 font-semibold text-lg">Nom du template : ' + '<span class="underline">' + nameInput.value +'</span></h3>';
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var codeSelect = document.getElementById('codeSelect');
    var nameField = document.getElementById('create_template_subject');

    codeSelect.addEventListener('change', function () {
        var selectedCode = codeSelect.value;
        nameField.value += selectedCode;
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var closeButton = document.getElementById('close-button');
    var divToHide = document.getElementById('hiddenDiv');

    closeButton.addEventListener('click', function (event) {
        event.preventDefault();
        divToHide.style.display = 'none';
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var parentElement = document.body;

    parentElement.addEventListener('click', function (event) {
       if (event.target && (event.target.id === 'show-div-link' || event.target.closest('#show-div-link'))) {
            var divToShow = document.getElementById('hiddenDiv');

           if (divToShow.style.display === 'none') {
                divToShow.style.display = 'block';
            } else {
                divToShow.style.display = 'none';
            }
        }
    });
});

