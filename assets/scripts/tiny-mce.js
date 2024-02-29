import 'flowbite';
import tinymce from 'tinymce/tinymce'
import 'tinymce/themes/silver/theme'
import 'tinymce/models/dom/model'
import 'tinymce/icons/default/icons'
import 'tinymce/skins/ui/oxide/skin'
import 'tinymce/skins/ui/oxide/content'
document.addEventListener('DOMContentLoaded', function () {
    tinymce.init({
        selector: '.editor',
        height:'80vh',
        editable_class: 'tiny-editable',
        content_style: `
      body {
        margin: 0px;
      }

      /* Edit area functional css */
      .tiny-editable {
        position: relative;
      }
      .tiny-editable:hover:not(:focus),
      .tiny-editable:focus {
        outline: 3px solid #b4d7ff;
        outline-offset: 4px;
        outline-offset: 4px;
      }

      /* Create an edit placeholder */
      .tiny-editable:empty::before,
      .tiny-editable:has(> br[data-mce-bogus]:first-child)::before {
        content: "Ecrivez ici...";
        position: absolute;
        top: 0;
        left: 0;
        color: #999;
      }
    `
    });
    // CONTENU

    document.addEventListener('DOMContentLoaded', function () {
        document.addEventListener('mousedown', function (event) {
            const clickedElement = event.target.closest('[contenteditable="true"]');
            if (clickedElement) {
                event.preventDefault(); // Empêcher le comportement par défaut pour éviter la perte de focus

                if (event.target === clickedElement) {
                    const range = document.createRange();
                    range.selectNodeContents(clickedElement);
                    const selection = window.getSelection();
                    selection.removeAllRanges();
                    selection.addRange(range);
                }
                clickedElement.focus(); // Focus sur l'élément éditable au clic
            }
        });
    });



    // ---------------------------------OPTION GRAS----------------------------------------------
    document.getElementById('boldButton').addEventListener('click', function (event) {
        event.preventDefault();
        boldText();
    });

    function boldText() {
        tinymce.activeEditor.execCommand('bold');
    }

    // -----------------------------------------OPTION COULEUR PICKER-----------------------------------------------------------------

    document.getElementById('colorPicker').addEventListener('input', function (event) {
        var color = event.target.value;
        changeTextColor(color);
    });

    function changeTextColor(color) {
        tinymce.activeEditor.execCommand('forecolor', false, color);
    }

    // -----------------------------------------OPTION ITALIQUE-----------------------------------------------------------------

    document.getElementById('italicButton').addEventListener('click', function (event) {
        event.preventDefault();
        italicText();
    });

    function italicText() {
        tinymce.activeEditor.execCommand('italic');
    }

    // -----------------------------------------OPTION SOULIGNAGE-----------------------------------------------------------------

    document.getElementById('underlineButton').addEventListener('click', function (event) {
        event.preventDefault();
        underlineText();
    });

    function underlineText() {
        tinymce.activeEditor.execCommand('underline');
    }

    // -----------------------------------------OPTION ALIGNEMENT GAUCHE-----------------------------------------------------------------

    document.getElementById('alignLeftButton').addEventListener('click', function (event) {
        event.preventDefault();
        justifyLeft();
    });

    function justifyLeft() {
        tinymce.activeEditor.execCommand('JustifyLeft');
    }

    // -----------------------------------------OPTION ALIGNEMENT CENTRE-----------------------------------------------------------------

    document.getElementById('alignCenterButton').addEventListener('click', function (event) {
        event.preventDefault();
        justifyCenter();
    });

    document.getElementById('btnAlignCenter').addEventListener('click', function (event) {
        event.preventDefault();
        justifyCenter();
    });

    function justifyCenter() {
        tinymce.activeEditor.execCommand('JustifyCenter');
    }

    // -----------------------------------------OPTION ALIGNEMENT DROIT-----------------------------------------------------------------

    document.getElementById('alignRightButton').addEventListener('click', function (event) {
        event.preventDefault();
        justifyRight();
    });

    function justifyRight() {
        tinymce.activeEditor.execCommand('JustifyRight');
    }

    // -----------------------------------------OPTION PREVIEW-----------------------------------------------------------------
    var previewButton = document.getElementById('previewButton');

    // Récupérer la fenêtre modale et son contenu
    var previewModal = document.getElementById('previewModal');
    var previewContent = document.getElementById('previewContent');

    // Ajouter un écouteur d'événement au clic sur le bouton d'aperçu
    previewButton.addEventListener('click', function (event) {
        // Empêcher le comportement par défaut du bouton (éviter de soumettre un formulaire ou de suivre un lien)
        event.preventDefault();

        // Récupérer l'iframe
        var iframe = document.getElementById('create_template_body_ifr');

        // Vérifier si l'iframe a été trouvé
        if (iframe) {
            // Récupérer le contenu de l'iframe
            var iframeContent = iframe.contentDocument || iframe.contentWindow.document;

            // Insérer le contenu de l'iframe dans la fenêtre modale
            previewContent.innerHTML = iframeContent.body.innerHTML;

            // Afficher la fenêtre modale
            previewModal.classList.remove('hidden');
        } else {
            console.error("iframe not found");
        }
    });

    // Ajouter un écouteur d'événement au clic sur le bouton de fermeture
    var closePreviewButton = document.getElementById('closeButton');
    closePreviewButton.addEventListener('click', function () {
        // Masquer la fenêtre modale
        previewModal.classList.add('hidden');
    });

    // -----------------------------------------OPTION AJOUTER BOUTON-----------------------------------------------------------------

    document.getElementById('buttonButton').addEventListener('click', function (event) {
        event.preventDefault();
        openButtonModal();
    });

    function openButtonModal() {
        var link = prompt("Entrez le lien pour le bouton :", "");
        var backgroundColor = prompt("Entrez la couleur de fond pour le bouton (en hexadécimal) :", "#ffffff");
        var textColor = prompt("Entrez la couleur du texte pour le bouton (en hexadécimal) :", "#000000");
        var borderColor = prompt("Entrez la couleur de bordure pour le bouton (en hexadécimal) :", "#000");
        var borderRadius = prompt("Entrez en px l'épaisseur des bords arrondis ? (uniquement les chiffres et entrez 0 pour que ce ne soit pas arrondi)", "");
        var paddingContent = prompt("Entrez la largeur autour du texte dans votre bouton (en chiffre) :","")
        var buttonContent = '<a href=' + link + ' style="border-radius:' + borderRadius + 'px;background-color: ' + backgroundColor + '; border: solid ' + borderColor + ' 1px; color: ' + textColor + '; padding:' + paddingContent +'px;';
        buttonContent += '">Nouveau bouton</a>';
        tinymce.activeEditor.execCommand('mceInsertContent', false, buttonContent);
    }

    // -----------------------------------------OPTION AJOUTER VIDEO-----------------------------------------------------------------

    document.getElementById('videoButton').addEventListener('click', function (event) {
        event.preventDefault();
        openVideoUploadModal();
    });

    function openVideoUploadModal() {
        var input = document.createElement('input');
        input.type = 'file';
        input.accept = 'video/*';
        input.onchange = function () {
            var file = input.files[0];
            uploadVideo(file);
        };
        input.click();
    }

    function uploadVideo(file) {
        var formData = new FormData();
        formData.append('file', file);

        fetch('/upload', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    tinymce.activeEditor.execCommand('mceInsertContent', false, '<video data-mce-src="' + data.path + '" src="' + data.path + '" controls></video>');
                } else {
                    console.error('Erreur lors du téléchargement de la vidéo : ' + data.error);
                }
            })
            .catch(error => {
                console.error('Erreur lors du téléchargement de la vidéo : ' + error.message);
            });
    }

    // -----------------------------------------OPTION AJOUTER SEPARATEUR-----------------------------------------------------------------

    document.getElementById('separatorButton').addEventListener('click', function (event) {
        event.preventDefault();
        insertSeparator();
    });

    function insertSeparator() {
        tinymce.activeEditor.execCommand('mceInsertContent', false, '<hr class="tiny-editable" style="border: 0; height: 1px; background-color: black;">');
    }

    // -----------------------------------------OPTION SELECTION POLICE-----------------------------------------------------------------

    document.getElementById('fontSelect').addEventListener('change', function (event) {
        event.preventDefault();
        fontSelect();
    });

    document.getElementById('fontSelectToolbar').addEventListener('change', function (event) {
        event.preventDefault();
        fontSelect();
    });

    function fontSelect() {
        var font = event.target.value;
        tinymce.activeEditor.execCommand('fontName', false, font);
    }

    // -----------------------------------------OPTION AJOUTER ESPACE-----------------------------------------------------------------

    document.getElementById('spacerButton').addEventListener('click', function (event) {
        event.preventDefault();
        addSpacer();
    });

    function addSpacer() {
        const cursorPosition = tinymce.activeEditor.selection.getRng();

        // Créer un nouvel élément div
        const spacerDiv = document.createElement('div');
        spacerDiv.className = 'py-4'; // Ajouter les classes CSS nécessaires

        // Insérer le nouvel élément div à l'emplacement du curseur
        if (cursorPosition.startContainer.nodeType === 3) {
            // Si le curseur est à l'intérieur d'un nœud texte, insérer le div après le nœud texte
            cursorPosition.startContainer.parentNode.insertBefore(spacerDiv, cursorPosition.startContainer.nextSibling);
        } else {
            // Sinon, insérer le div comme enfant du nœud parent
            cursorPosition.startContainer.appendChild(spacerDiv);
        }
    }



    // -----------------------------------------OPTION AJOUTER CODE HTML-----------------------------------------------------------------

    document.getElementById('listButton').addEventListener('click', function (event) {
        event.preventDefault();
        addList();
    });

    function addList() {
        const list = '<ul><li>Premier</li><li>Deuxième</li><li>Troisième</li></ul>';
        tinymce.activeEditor.insertContent(list);
    }


    // -----------------------------------------OPTION CHOISIR TAILLE POLICE-----------------------------------------------------------------

    document.getElementById('fontSizeSelect').addEventListener('change', function (event) {
        event.preventDefault();
        var size = event.target.value;
        tinymce.activeEditor.execCommand('fontSize', false, size);
    });

    // -----------------------------------------OPTION AJOUTER TITRE-----------------------------------------------------------------

    document.getElementById('titleButton').addEventListener('click', function (event) {
        event.preventDefault();
        insertTitle();
    });
    function insertTitle() {
        tinymce.activeEditor.execCommand('mceInsertContent', false, '<h1 class="tiny-editable font-bold text-xl"></h1>');
    }

    // -----------------------------------------OPTION DIV-----------------------------------------------------------------
    function insertDiv() {
        tinymce.activeEditor.execCommand('mceInsertContent', false, '<div class="tiny-editable"><p>Voici une div</p></div><br>');
    }

    document.getElementById('divButton').addEventListener('click', function (event) {
        event.preventDefault();
        insertDiv();
    });

    function insertDiv2() {
        tinymce.activeEditor.execCommand('mceInsertContent', false, '<div style="display:flex">\n' +
            '    <div style="width:50%; --tw-bg-opacity: 1; background-color: rgb(209 213 219 / var(--tw-bg-opacity)); padding:1rem;">\n' +
            '        <h2 class="text-lg font-semibold">Colonne 1</h2>\n' +
            '        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dictum, risus id vulputate dapibus, justo sapien facilisis ipsum, sit amet volutpat sapien magna vel metus.</p>\n' +
            '    </div>\n' +
            '    <div style="width:50%; --tw-bg-opacity: 1; background-color: rgb(209 213 219 / var(--tw-bg-opacity)); padding:1rem;">\n' +
            '        <h2 class="text-lg font-semibold">Colonne 2</h2>\n' +
            '        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dictum, risus id vulputate dapibus, justo sapien facilisis ipsum, sit amet volutpat sapien magna vel metus.</p>\n' +
            '    </div>\n' +
            '</div>');
    }

    document.getElementById('div2Button').addEventListener('click', function (event) {
        event.preventDefault();
        insertDiv2();
    });

    function insertDiv3() {
        tinymce.activeEditor.execCommand('mceInsertContent', false, '<div style="display:flex">\n' +
            '    <div style="width:33%; --tw-bg-opacity: 1; background-color: rgb(209 213 219 / var(--tw-bg-opacity)); padding:1rem;">\n' +
            '        <h2 class="text-lg font-semibold">Colonne 1</h2>\n' +
            '        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dictum, risus id vulputate dapibus, justo sapien facilisis ipsum, sit amet volutpat sapien magna vel metus.</p>\n' +
            '    </div>\n' +
            '    <div style="width:33%; --tw-bg-opacity: 1; background-color: rgb(209 213 219 / var(--tw-bg-opacity)); padding:1rem;">\n' +
            '        <h2 class="text-lg font-semibold">Colonne 2</h2>\n' +
            '        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dictum, risus id vulputate dapibus, justo sapien facilisis ipsum, sit amet volutpat sapien magna vel metus.</p>\n' +
            '    </div>\n' +
            ' <div style="width:33%; --tw-bg-opacity: 1; background-color: rgb(209 213 219 / var(--tw-bg-opacity)); padding:1rem;">\n' +
                '        <h2 class="text-lg font-semibold">Colonne 3</h2>\n' +
                '        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dictum, risus id vulputate dapibus, justo sapien facilisis ipsum, sit amet volutpat sapien magna vel metus.</p>\n' +
                '    </div>\n' +
            '</div>');
    }

    document.getElementById('div3Button').addEventListener('click', function (event) {
        event.preventDefault();
        insertDiv3();
    });

    // -----------------------------------------OPTION SECTION-----------------------------------------------------------------
    function insertSection() {
        tinymce.activeEditor.execCommand('mceInsertContent', false, '<section class="tiny-editable w-full" style="height:100px;background-color:#f9f9fb"></section>');
    }

    document.getElementById('sectionButton').addEventListener('click', function (event) {
        event.preventDefault();
        insertSection();
    });

    // -----------------------------------------OPTION PARAGRAPHE-----------------------------------------------------------------
    document.getElementById('paragraphButton').addEventListener('click', function (event) {
        event.preventDefault();
        insertParagraph();
    });
    function insertParagraph() {
        tinymce.activeEditor.execCommand('mceInsertContent', false, '<p class="tiny-editable "></p>');
    }

    // -----------------------------------------OPTION AJOUTER IMAGE-----------------------------------------------------------------
    function uploadImage(file) {
        var formData = new FormData();
        formData.append('file', file);

        fetch('/upload', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    tinymce.activeEditor.execCommand('mceInsertContent', false, '<img class="tiny-editable" data-mce-src="' + data.path + '" src="' + data.path + '">');
                } else {
                    console.error('Erreur lors du téléchargement de l\'image : ' + data.error);
                }
            })
            .catch(error => {
                console.error('Erreur lors du téléchargement de l\'image : ' + error.message);
            });
    }


    function changeImage() {
        openImageSelectionDialog();
    }

    // Fonction pour redimensionner l'image
    function resizeImage() {
        const img = tinymce.activeEditor.selection.getNode();
        let defaultWidth = 300; // Valeur par défaut pour la largeur de l'image

        if (img && img.nodeName === 'IMG') {
            defaultWidth = img.width || defaultWidth; // Utilise la largeur actuelle de l'image s'il y en a une
        }

        const width = prompt('Entrez la nouvelle largeur de l\'image (en pixels) :', defaultWidth);

        if (img && img.nodeName === 'IMG' && width && !isNaN(width)) {
            img.width = width;
        } else {
            alert('Veuillez sélectionner une image valide et entrer une largeur valide.');
        }
    }


// Écouteur d'événements pour les boutons de la barre d'outils de l'image
    document.getElementById('btnChangeImage').addEventListener('click', function (event) {
        event.preventDefault();
        changeImage();
    });

    document.getElementById('btnResizeImage').addEventListener('click', function (event) {
        event.preventDefault();
        resizeImage();
    });


    document.getElementById('btnBold').addEventListener('click', function (event) {
        event.preventDefault();
        boldText();
    });

    document.getElementById('btnItalic').addEventListener('click', function (event) {
        event.preventDefault();
        italicText();
    });

    document.getElementById('btnUnderline').addEventListener('click', function (event) {
        event.preventDefault();
        underlineText();
    });

    function openImageSelectionDialog() {
        var input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';
        input.onchange = function () {
            var file = input.files[0];
            uploadImage(file);
        };
        input.click();
    }

    document.getElementById('imageButton').addEventListener('click', function (event) {
        event.preventDefault();
        openImageSelectionDialog();
    });

    tinymce.activeEditor.on('click', function (e) {
        if (e.target.nodeName === 'IMG') {
            showImageToolbar(e.target);
        } else {
            hideImageToolbar();
        }
    });

    function showImageToolbar() {
        const toolbar = document.getElementById('imageToolbar');
        toolbar.classList.remove('hidden');
    }

    const closeButton = document.getElementById('closeImageToolbar');
    closeButton.addEventListener('click', function (event) {
        event.preventDefault();
        hideImageToolbar();
    });

    function hideImageToolbar() {
        const toolbar = document.getElementById('imageToolbar');
        toolbar.classList.add('hidden');
    }

    tinymce.activeEditor.on('click', function (e) {
        if (e.target.nodeName === 'P' || e.target.nodeName === 'H1' || e.target.nodeName === 'H2' || e.target.nodeName === 'H3' || e.target.nodeName === 'H4' || e.target.nodeName === 'H5' || e.target.nodeName === 'H6') {
            showTextToolbar(e.target);
        } else {
            hideTextToolbar();
        }
    });

    tinymce.activeEditor.on('click keyup', function (e) {
        const selectedText = tinymce.activeEditor.selection.getContent({format: 'text'});
        const toolbar = document.getElementById('textToolbar');
        if (selectedText !== '') {
            const selection = tinymce.activeEditor.selection.getRng();
            const selectedParentElement = selection.commonAncestorContainer.parentElement;
            if (selectedParentElement.nodeName === 'P' || selectedParentElement.nodeName === 'H1' || selectedParentElement.nodeName === 'H2' || selectedParentElement.nodeName === 'H3' || selectedParentElement.nodeName === 'H4' || selectedParentElement.nodeName === 'H5' || selectedParentElement.nodeName === 'H6') {
                showTextToolbar(selectedParentElement);
            } else {
                hideTextToolbar();
            }
        } else {
            hideTextToolbar();
        }
    });

    tinymce.activeEditor.on('click keyup', function (e) {
        const selectedText = tinymce.activeEditor.selection.getContent({format: 'text'});
        const toolbar = document.getElementById('textToolbar');
        if (selectedText !== '') {
            const selection = tinymce.activeEditor.selection.getRng();
            const selectedParentElement = selection.commonAncestorContainer.parentElement;
            if (selectedParentElement.nodeName === 'P' || selectedParentElement.nodeName === 'H1' || selectedParentElement.nodeName === 'H2' || selectedParentElement.nodeName === 'H3' || selectedParentElement.nodeName === 'H4' || selectedParentElement.nodeName === 'H5' || selectedParentElement.nodeName === 'H6') {
                showTextToolbar(selectedParentElement);
            } else {
                hideTextToolbar();
            }
        } else if (e.target.nodeName === 'P' || e.target.nodeName === 'H1' || e.target.nodeName === 'H2' || e.target.nodeName === 'H3' || e.target.nodeName === 'H4' || e.target.nodeName === 'H5' || e.target.nodeName === 'H6') {
            showTextToolbar(e.target);
        } else {
            hideTextToolbar();
        }
    });



    function showTextToolbar(targetElement) {
        const toolbar = document.getElementById('textToolbar');
        toolbar.classList.remove('hidden');
    }

    const closeTextButton = document.getElementById('closeTextToolbar');
    closeTextButton.addEventListener('click', function (event) {
        event.preventDefault();
        hideTextToolbar();
    });

    function hideTextToolbar() {
        const toolbar = document.getElementById('textToolbar');
        toolbar.classList.add('hidden');
    }

    const buttons = document.querySelectorAll('.button');

    buttons.forEach(button => {
        button.addEventListener('dragstart', function () {
        });

        button.addEventListener('dragend', function (event) {
            event.target.click();
        });
    });
});


