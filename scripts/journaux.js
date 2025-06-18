const dataTableLang = {
  fr: {
    emptyTable: "Aucune donnée disponible dans la table",
    info: "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
    infoEmpty: "Affichage de 0 à 0 sur 0 entrées",
    infoFiltered: "(filtrées depuis un total de _MAX_ entrées)",
    lengthMenu: "Afficher _MENU_ entrées",
    loadingRecords: "Chargement...",
    processing: "Traitement...",
    search: "Rechercher:",
    zeroRecords: "Aucun enregistrement correspondant trouvé",
    paginate: {
      first: "Premier",
      last: "Dernier",
      next: "Suivant",
      previous: "Précédent"
    }
  },
  en: {
    search: "Search:",
    paginate: { first: "First", last: "Last", next: "Next", previous: "Previous" }
  },
  it: {
    emptyTable: "Nessun dato disponibile nella tabella",
    info: "Visualizzazione da _START_ a _END_ di _TOTAL_ elementi",
    infoEmpty: "Visualizzazione di 0 su 0 di 0 elementi",
    infoFiltered: "(filtrati da _MAX_ elementi totali)",
    lengthMenu: "Mostra _MENU_ elementi",
    loadingRecords: "Caricamento...",
    processing: "Elaborazione...",
    search: "Cerca:",
    zeroRecords: "Nessun record corrispondente trovato",
    paginate: {
      first: "Primo",
      last: "Ultimo",
      next: "Successivo",
      previous: "Precedente"
    }
  },
  de: {
    emptyTable: "Keine Daten in der Tabelle vorhanden",
    info: "Zeige _START_ bis _END_ von _TOTAL_ Einträgen",
    infoEmpty: "Zeige 0 bis 0 von 0 Einträgen",
    infoFiltered: "(gefiltert von _MAX_ Einträgen gesamt)",
    lengthMenu: "Zeige _MENU_ Einträge",
    loadingRecords: "Laden...",
    processing: "Verarbeite...",
    search: "Suchen:",
    zeroRecords: "Keine passenden Einträge gefunden",
    paginate: {
      first: "Erste",
      last: "Letzte",
      next: "Nächste",
      previous: "Vorherige"
    }
  }
};

function loadUploadedLogs() {
  const container = document.getElementById("logs-container");

  container.innerHTML = `
    <div class="loading-box">
      <div class="spinner"></div>
      <p class="loading-text">${window.lang.loading_logs}<span id="dots"></span></p>
      <p class="loading-subtext">${window.lang.loading_wait}</p>
    </div>
  `;

  container.style.display = "block";

  let dotCount = 0;
  const dots = document.getElementById("dots");
  const dotInterval = setInterval(() => {
    dotCount = (dotCount + 1) % 4;
    dots.textContent = '.'.repeat(dotCount);
  }, 500);

  const xhr = new XMLHttpRequest();
  xhr.open("GET", "load_uploaded_logs.php?lang=" + window.langCode, true);

  xhr.onload = function () {
    clearInterval(dotInterval);
    if (xhr.status === 200) {
      container.innerHTML = xhr.responseText;

      const table = $('#logTable');
      if ($.fn.DataTable.isDataTable(table)) {
        table.DataTable().destroy();
      }

      table.DataTable({
        language: dataTableLang[window.langCode || 'fr'],
        pageLength: 25,
        lengthChange: true,
        paging: true,
        searching: true,
        ordering: true,
        dom: 'Blfrtip',
        buttons: [
          { extend: 'copy', text: '<i class="fa fa-copy"></i> ' + window.lang.copy },
          { extend: 'excel', text: '<i class="fa fa-file-excel"></i> ' + window.lang.excel },
          {
            extend: 'pdfHtml5',
            text: '<i class="fa fa-file-pdf"></i> ' + window.lang.pdf,
            orientation: 'landscape',
            pageSize: 'A4',
            exportOptions: { columns: ':visible' }
          },
          {
            extend: 'print',
            text: '<i class="fa fa-print"></i> ' + window.lang.print,
            customize: function (win) {
              const css = '@page { size: landscape; }';
              const head = win.document.head || win.document.getElementsByTagName('head')[0];
              const style = win.document.createElement('style');
              style.type = 'text/css';
              style.media = 'print';
              if (style.styleSheet) style.styleSheet.cssText = css;
              else style.appendChild(win.document.createTextNode(css));
              head.appendChild(style);
            }
          }
        ],
        initComplete: function () {
          document.querySelectorAll('.dt-button').forEach(btn => btn.classList.add('styled-button'));
        }
      });

    } else {
      container.innerHTML = `<p style='color:red'>${window.lang.load_error_uploaded}</p>`;
    }
  };

  xhr.send();
}

function loadLocalLogs() {
  const container = document.getElementById("logs-container");

  container.innerHTML = `
    <div class="loading-box">
      <div class="spinner"></div>
      <p class="loading-text">${window.lang.loading_logs}<span id="dots"></span></p>
      <p class="loading-subtext">${window.lang.loading_wait}</p>
    </div>
  `;

  container.style.display = "block";

  let dotCount = 0;
  const dots = document.getElementById("dots");
  const dotInterval = setInterval(() => {
    dotCount = (dotCount + 1) % 4;
    dots.textContent = '.'.repeat(dotCount);
  }, 500);

  const xhr = new XMLHttpRequest();
  xhr.open("GET", "load_local_logs.php?lang=" + window.langCode, true);

  xhr.onload = function () {
    clearInterval(dotInterval);
    if (xhr.status === 200) {
      container.innerHTML = xhr.responseText;

      const table = $('#logTable');
      if ($.fn.DataTable.isDataTable(table)) {
        table.DataTable().destroy();
      }

      table.DataTable({
        language: dataTableLang[window.langCode || 'fr'],
        pageLength: 25,
        lengthChange: true,
        paging: true,
        searching: true,
        ordering: true,
        dom: 'Blfrtip',
        buttons: [
          { extend: 'copy', text: '<i class="fa fa-copy"></i> ' + window.lang.copy },
          { extend: 'excel', text: '<i class="fa fa-file-excel"></i> ' + window.lang.excel },
          {
            extend: 'pdfHtml5',
            text: '<i class="fa fa-file-pdf"></i> ' + window.lang.pdf,
            orientation: 'landscape',
            pageSize: 'A4',
            exportOptions: { columns: ':visible' }
          },
          {
            extend: 'print',
            text: '<i class="fa fa-print"></i> ' + window.lang.print,
            customize: function (win) {
              const css = '@page { size: landscape; }';
              const head = win.document.head || win.document.getElementsByTagName('head')[0];
              const style = win.document.createElement('style');
              style.type = 'text/css';
              style.media = 'print';
              if (style.styleSheet) style.styleSheet.cssText = css;
              else style.appendChild(win.document.createTextNode(css));
              head.appendChild(style);
            }
          }
        ],
        initComplete: function () {
          document.querySelectorAll('.dt-button').forEach(btn => btn.classList.add('styled-button'));
        }
      });

    } else {
      container.innerHTML = `<p style='color:red'>${window.lang.load_error_local}</p>`;
    }
  };

  xhr.send();
}

function loadRemoteLogs(folder) {
  if (!folder) return;

  const container = document.getElementById("logs-container");

  container.innerHTML = `
    <div class="loading-box">
      <div class="spinner"></div>
      <p class="loading-text">${window.lang.loading_logs}<span id="dots"></span></p>
      <p class="loading-subtext">${window.lang.loading_wait}</p>
    </div>
  `;

  container.style.display = "block";

  let dotCount = 0;
  const dots = document.getElementById("dots");
  const dotInterval = setInterval(() => {
    dotCount = (dotCount + 1) % 4;
    dots.textContent = '.'.repeat(dotCount);
  }, 500);

  const xhr = new XMLHttpRequest();
  xhr.open("GET", "load_remote_logs.php?folder=" + encodeURIComponent(folder) + "&lang=" + window.langCode, true);

  xhr.onload = function () {
    clearInterval(dotInterval);
    if (xhr.status === 200) {
      container.innerHTML = xhr.responseText;

      const table = $('#logTable');
      if ($.fn.DataTable.isDataTable(table)) {
        table.DataTable().destroy();
      }

      table.DataTable({
        language: dataTableLang[window.langCode || 'fr'],
        pageLength: 25,
        lengthChange: true,
        paging: true,
        searching: true,
        ordering: true,
        dom: 'Blfrtip',
        buttons: [
          { extend: 'copy', text: '<i class="fa fa-copy"></i> ' + window.lang.copy },
          { extend: 'excel', text: '<i class="fa fa-file-excel"></i> ' + window.lang.excel },
          {
            extend: 'pdfHtml5',
            text: '<i class="fa fa-file-pdf"></i> ' + window.lang.pdf,
            orientation: 'landscape',
            pageSize: 'A4',
            exportOptions: { columns: ':visible' }
          },
          {
            extend: 'print',
            text: '<i class="fa fa-print"></i> ' + window.lang.print,
            customize: function (win) {
              const css = '@page { size: landscape; }';
              const head = win.document.head || win.document.getElementsByTagName('head')[0];
              const style = win.document.createElement('style');
              style.type = 'text/css';
              style.media = 'print';
              if (style.styleSheet) style.styleSheet.cssText = css;
              else style.appendChild(win.document.createTextNode(css));
              head.appendChild(style);
            }
          }
        ],
        initComplete: function () {
          document.querySelectorAll('.dt-button').forEach(btn => btn.classList.add('styled-button'));
        }
      });

    } else {
      container.innerHTML = `<p style='color:red'>${window.lang.load_error_local}</p>`;
    }
  };

  xhr.send();
}

function loadSelectedServerLogs() {
  const select = document.getElementById('server-select');
  if (!select || select.value === 'local' || select.value === '') {
    loadLocalLogs();
  } else {
    loadRemoteLogs(select.value);
  }
}


function uploadLogs() {
  const files = document.getElementById('fileInput').files;
  if (files.length === 0) {
    alert(window.lang.select_files);
    return;
  }

  const formData = new FormData();
  formData.append("source", "upload");

  for (let i = 0; i < files.length; i++) {
    formData.append("xmlfiles[]", files[i]);
  }

  const xhr = new XMLHttpRequest();
  xhr.open("POST", location.pathname, true);

  const progressContainer = document.getElementById("progressContainer");
  const progressText = document.getElementById("progressText");
  const uploadProgress = document.getElementById("uploadProgress");

  progressContainer.style.display = "block";

  xhr.upload.addEventListener("progress", function (e) {
    if (e.lengthComputable) {
      const percent = Math.round((e.loaded / e.total) * 100);
      uploadProgress.value = percent;
      progressText.innerText = percent + "%";
    }
  });

  xhr.onload = function () {
    if (xhr.status === 200) {
      showMessage(window.lang.upload_success, "success");

      setTimeout(() => {
        progressContainer.style.display = "none";
        uploadProgress.value = 0;
        progressText.innerText = "0%";
      }, 500);

      loadUploadedLogs();
    } else {
      showMessage(window.lang.upload_error, "error");
    }
  };

  xhr.send(formData);
}

function toggleUpload(choice) {
  document.getElementById("upload-zone").style.display = (choice === 'upload') ? 'block' : 'none';

  if (choice === 'upload') {
    fetch('check_uploaded_logs.php?lang=' + window.langCode)
      .then(response => response.text())
      .then(result => {
        if (result.trim() === '1') {
          loadUploadedLogs();
        } else {
          document.getElementById("logs-container").innerHTML =
            `<p style='font-style: italic; color: #666;'>${window.lang.no_uploaded_found}</p>`;
        }
      })
      .catch(error => {
        console.error("Erreur lors de la vérification des fichiers uploadés", error);
      });
  }
}

function showMessage(text, type = "success") {
  const box = document.getElementById("messageBox");
  box.textContent = text;
  box.className = "show " + type;
  box.style.display = "block";

  setTimeout(() => {
    box.style.display = "none";
  }, 4000);
}

document.addEventListener("DOMContentLoaded", function () {
  const selected = document.querySelector('input[name="source"]:checked');
  const source = selected ? selected.value : 'local';
  toggleUpload(source);

  if (source === "upload") {
    loadUploadedLogs();
  } else {
    loadSelectedServerLogs();
  }

  const serverSelect = document.getElementById('server-select');
  if (serverSelect) {
    serverSelect.addEventListener('change', function () {
      loadSelectedServerLogs();
    });
  }

  const remoteSelect = document.getElementById('remote-select');
  if (remoteSelect) {
    remoteSelect.addEventListener('change', function () {
      if (remoteSelect.value) {
        loadRemoteLogs(remoteSelect.value);
      }
    });
  }

  const fileInput = document.getElementById('fileInput');
  const fileChosen = document.getElementById('file-chosen');

  if (fileInput && fileChosen) {
    fileInput.addEventListener('change', function () {
      if (fileInput.files.length > 0) {
        fileChosen.textContent = `${fileInput.files.length} ${window.lang.file_chosen}`;
        toggleUpload('upload');
        uploadLogs();
      } else {
        fileChosen.textContent = window.lang.no_file_selected;
      }
    });
  }
});
