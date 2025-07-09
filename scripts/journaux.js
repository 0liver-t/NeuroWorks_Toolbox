// --- Variable globale pour la source sélectionnée
let currentSource = localStorage.getItem('log-source') || 'local';

// --- Fonctions d'internationalisation DataTables ---
function getDataTableLang() {
  return {
    emptyTable:     window.lang?.datatable_emptyTable   || "No data available in table",
    info:           window.lang?.datatable_info         || "Showing _START_ to _END_ of _TOTAL_ entries",
    infoEmpty:      window.lang?.datatable_infoEmpty    || "Showing 0 to 0 of 0 entries",
    infoFiltered:   window.lang?.datatable_infoFiltered || "(filtered from _MAX_ total entries)",
    lengthMenu:     window.lang?.datatable_lengthMenu   || "Show _MENU_ entries",
    loadingRecords: window.lang?.datatable_loadingRecords || "Loading...",
    processing:     window.lang?.datatable_processing   || "Processing...",
    search:         window.lang?.datatable_search       || "Search:",
    zeroRecords:    window.lang?.datatable_zeroRecords  || "No matching records found",
    paginate: {
      first:    window.lang?.datatable_paginate_first    || "First",
      last:     window.lang?.datatable_paginate_last     || "Last",
      next:     window.lang?.datatable_paginate_next     || "Next",
      previous: window.lang?.datatable_paginate_previous || "Previous"
    },
    thousands: '\u202f'
  };
}

// --- Bouton mode compact DataTables ---
function addCompactButton() {
  setTimeout(function() {
    if ($('#toggle-compact').length === 0 && $('.dt-buttons').length) {
      const compactBtn = $(`\
        <button id="toggle-compact" type="button" title="${window.lang?.compact_mode || 'Mode compact'}" style="margin-left:0.7em;">\
          <i class="fa fa-compress"></i> <span>${window.lang?.compact_mode || 'Mode compact'}</span>\
        </button>\
      `);

      compactBtn.on('click', function() {
        $('body').toggleClass('compact-table');
        const isCompact = $('body').hasClass('compact-table');
        if (isCompact) {
          $(this).find('span').text(window.lang?.normal_mode || 'Mode normal');
          $(this).find('i').removeClass('fa-compress').addClass('fa-expand');
          $(this).attr('title', window.lang?.normal_mode || 'Mode normal');
          $(this).addClass('active');
        } else {
          $(this).find('span').text(window.lang?.compact_mode || 'Mode compact');
          $(this).find('i').removeClass('fa-expand').addClass('fa-compress');
          $(this).attr('title', window.lang?.compact_mode || 'Mode compact');
          $(this).removeClass('active');
        }
      });

      $('.dt-buttons').append(compactBtn);
    }
  }, 0);
}

// --- Message utilisateur ---
function showMessage(text, type = "success") {
  const box = document.getElementById("messageBox");
  box.innerHTML = text;
  box.className = "show " + type;
  box.style.display = "block";
  setTimeout(() => {
    box.style.display = "none";
  }, 6000);
}

// --- Fonctions utilitaires ---
let table;
let currentServerSide = true;

function showLoader(show) { $('#loader').toggle(!!show); }
function showError(msg)   { $('#logs-error').text(msg).show(); }
function hideError()      { $('#logs-error').hide().text(''); }

// --- Formatage des dates ---
function renderDateCell(data) {
  if (!data) return '';
  const lang = window.langCode || 'fr';
  if (window.moment) {
    let m = window.moment(data);
    if (m.isValid()) {
      if (lang === 'en') {
        return m.locale('en').format('MM/DD/YYYY - hh:mm:ss A');
      } else {
        return m.locale(lang).format('DD/MM/YYYY - HH:mm:ss');
      }
    }
  }
  let d = new Date(data);
  if (!isNaN(d)) {
    let day   = ('0' + d.getDate()).slice(-2);
    let month = ('0' + (d.getMonth() + 1)).slice(-2);
    let year  = d.getFullYear();
    let hour  = d.getHours();
    let min   = ('0' + d.getMinutes()).slice(-2);
    let sec   = ('0' + d.getSeconds()).slice(-2);

    if (lang === 'en') {
      let ampm = hour >= 12 ? 'PM' : 'AM';
      let hour12 = hour % 12;
      if (hour12 === 0) hour12 = 12;
      hour12 = ('0' + hour12).slice(-2);
      return `${month}/${day}/${year} - ${hour12}:${min}:${sec} ${ampm}`;
    } else {
      hour = ('0' + hour).slice(-2);
      return `${day}/${month}/${year} - ${hour}:${min}:${sec}`;
    }
  }
  return data;
}

// --- Affichage conditionnel de l'upload zone selon la source ---
function updateUploadZoneVisibility(selectedSource) {
  if (selectedSource === 'user') {
    $('#upload-zone').show();
  } else {
    $('#upload-zone').hide();
  }
}

// --- Initialisation DataTable ---
function initDataTable() {
  if (table) {
    $('#logs-table').DataTable().clear().destroy();
    $('#logs-table').empty();
    $('#logs-table').append(
      `<thead><tr>
        <th>${window.lang?.date || 'Date/Heure'}</th>
        <th>${window.lang?.machine || 'Machine'}</th>
        <th>${window.lang?.user || 'Utilisateur'}</th>
        <th>${window.lang?.action || 'Action'}</th>
        <th>${window.lang?.event || 'Événement'}</th>
        <th>${window.lang?.desc || 'Description'}</th>
      </tr></thead>`
    );
  }

  $.fn.dataTable.ext.errMode = 'none';

  table = $('#logs-table').DataTable({
    serverSide: true,
    processing: true,
    searching: false,
    order: [[0, 'desc']],
    ajax: {
      url: 'load_logs.php',
      type: 'GET',
      data: function(d) {
        d.search = { value: $('#search-input').val() };
        d.source = currentSource;
      },
      error: function(xhr) { showLoader(false); showError(window.lang?.error_loading_logs || 'Erreur lors du chargement des logs.'); },
      beforeSend: function() { showLoader(true); hideError(); },
      complete:   function() { showLoader(false); }
    },
    columns: [
      { data: 'time', title: window.lang?.date || 'Date/Heure', render: renderDateCell },
      { data: 'mach',   title: window.lang?.machine || 'Machine' },
      { data: 'user',   title: window.lang?.user || 'Utilisateur' },
      { data: 'acID',   title: window.lang?.action || 'Action' },
      { data: 'evID',   title: window.lang?.event || 'Événement' },
      { data: 'evdesc', title: window.lang?.desc || 'Description' }
    ],
    createdRow: function(row, data, dataIndex) {
      if (data.acID && data.acID.toLowerCase().includes('deleting')) {
        $(row).addClass('tr-deleting');
      }
    },
    dom: '<"dtheadbar d-flex justify-content-between align-items-center"lBfp>rt<"dtfootbar d-flex justify-content-between align-items-center"ip>',
    buttons: [
      { 
        extend: 'pdfHtml5',
        text: '<i class="fa fa-file-pdf"></i> ' + (window.lang?.export_pdf || 'Exporter PDF'),
        orientation: 'landscape',
        pageSize: 'A4'
      },
      { extend: 'excelHtml5', text: '<i class="fa fa-file-excel"></i> ' + (window.lang?.export_excel || 'Exporter Excel') },
      { extend: 'print',      text: '<i class="fa fa-print"></i> ' + (window.lang?.print || 'Imprimer') }
    ],
    pageLength: 25,
    language: getDataTableLang(),
    initComplete: function () {
      document.querySelectorAll('.dt-button').forEach(btn => btn.classList.add('styled-button'));
      addCompactButton();
    }
  });

  table.on('error.dt', function(e, settings, techNote, message) {
    showLoader(false);
    showError(window.lang?.error_loading_logs || 'Erreur lors du chargement des logs.');
  });
}

// --- Reload DataTable avec la recherche courante ---
function reloadWithSearch() {
  hideError();
  if (!table) return;
  showLoader(true);
  table.ajax.reload(function() { showLoader(false); }, false);
}

// --- Vérification source et préchargement ---
function checkAndPrepareSource(source, callback) {
  $.getJSON('check_source.php?source=' + encodeURIComponent(source), function(res) {
    // Si dossier ou DB manquants mais des logs présents => indexation automatique
    if (!res.log_dir_exists) {
      showError('Le dossier des logs est introuvable.');
      if (typeof callback === "function") callback(false);
      return;
    }
    if (!res.db_exists && res.has_logs) {
      showLoader(true);
      // Reconstruit la base à la volée
      $.post('cache_setup.php', { action: 'rebuild', source: source })
        .done(function(resp) {
          showLoader(false);
          if (resp && resp.success) {
            showMessage("Base créée avec succès (" + resp.count + " lignes).", "success");
            if (typeof callback === "function") callback(true, res.has_logs);
          } else {
            showError(resp && resp.message ? resp.message : "Erreur lors de la création de la base.");
            if (typeof callback === "function") callback(false);
          }
        })
        .fail(function() {
          showLoader(false);
          showError("Erreur AJAX lors de la création de la base.");
          if (typeof callback === "function") callback(false);
        });
    } else if (res.db_exists && !res.has_logs) {
      // Base existe mais aucun log XML -> affichage message custom
      if (typeof callback === "function") callback(true, false);
    } else {
      // Tout est prêt
      if (typeof callback === "function") callback(true, res.has_logs);
    }
  });
}

// --- Sélection du log source avec auto-indexation ---
function setupSourceSelector() {
  const $sel = $('#log-source-select');
  if ($sel.length === 0) return;
  const local = localStorage.getItem('log-source');
  if (local && $sel.find('option[value="' + local + '"]').length) {
    $sel.val(local);
    currentSource = local;
  } else {
    currentSource = $sel.find('option').first().val();
    $sel.val(currentSource);
    localStorage.setItem('log-source', currentSource);
  }
  $sel.on('change', function() {
    currentSource = this.value;
    localStorage.setItem('log-source', currentSource);

    // Affiche ou cache la zone d'upload selon la source choisie
    updateUploadZoneVisibility(currentSource);

    // Vider le tableau dès la sélection (pour éviter toute confusion)
    if ($.fn.DataTable.isDataTable('#logs-table')) {
      $('#logs-table').DataTable().clear().draw();
    }

    checkAndPrepareSource(currentSource, function(ready, hasLogs) {
      if (ready) {
        if (!hasLogs) {
          $('#logs-error').text("Aucun fichier de logs trouvé pour cette source.").show();
        } else {
          hideError();
          reloadWithSearch();
        }
      }
      // Sinon, l'erreur a déjà été affichée
    });
  });
}

// --- Ready ---
$(document).ready(function() {
  setupSourceSelector();

  // Affichage zone upload dès le chargement
  updateUploadZoneVisibility($('#log-source-select').val());

  // Vérification au chargement de la page (sur source courante)
  checkAndPrepareSource(currentSource, function(ready, hasLogs) {
    if (ready && hasLogs) {
      initDataTable();
    } else if (ready && !hasLogs) {
      $('#logs-error').text("Aucun fichier de logs trouvé pour cette source.").show();
    }
    // Sinon, erreur déjà affichée
  });

  $('#search-btn').on('click', reloadWithSearch);
  $('#search-input').on('keypress', function(e) {
    if (e.which === 13) reloadWithSearch();
  });

  $('#search-input').on('input', function() {
    if (this.value.length > 0) {
      $('#search-clear').show();
    } else {
      $('#search-clear').hide();
    }
  });

  $('#search-clear').on('click', function() {
    $('#search-input').val('');
    $('#search-clear').hide();
    reloadWithSearch();
  });
});

// --- Recharge DB ---
$('#reload-db-btn').on('click', function() {
  if (!confirm(window.lang?.confirm_reload_db || 'Cette opération va recharger et réindexer la base des logs depuis les fichiers XML.\nContinuer ?')) return;
  showLoader(true);
  $.post('cache_setup.php', { action: 'rebuild', source: currentSource })
    .done(function(resp) {
      showLoader(false);
      if (resp && resp.success) {
        showMessage(
          (window.lang?.reload_success || "Base rechargée avec succès") +
            '&nbsp;: <strong>' + resp.count + '</strong> ' +
            (window.lang?.rows_in_db || 'lignes en base.'),
          'success'
        );
        reloadWithSearch();
      } else {
        showMessage(resp && resp.message ? resp.message : (window.lang?.reload_error || "Erreur lors du rechargement."), 'error');
      }
    })
    .fail(function() {
      showLoader(false);
      showMessage(window.lang?.reload_error_ajax || 'Erreur AJAX lors du rechargement de la base.', 'error');
    });
});

// --- Upload AJAX log personnel ---
$('#upload-form').on('submit', function(e) {
  e.preventDefault();
  var formData = new FormData(this);
  formData.append('source', 'user');
  $('#upload-feedback').html('<i class="fa fa-spinner fa-spin"></i> Upload en cours...');

  $.ajax({
    url: 'upload_xml.php',
    type: 'POST',
    data: formData,
    contentType: false,
    processData: false,
    success: function(resp) {
      $('#upload-feedback').html(resp);

      // Test basique pour détecter succès (adapter si besoin)
      if (resp.toLowerCase().includes('uploadé')) {
        $('#upload-feedback').append('<br><i class="fa fa-spinner fa-spin"></i> Indexation des logs...');
        // Appel AJAX à cache_setup.php pour réindexer
        $.post('cache_setup.php', { action: 'rebuild', source: 'user' }, function(idxResp) {
          try {
            var data = (typeof idxResp === 'string') ? JSON.parse(idxResp) : idxResp;
            if (data.success) {
              $('#upload-feedback').append(
                "<br><span style='color:green;'>Indexation terminée, <strong>" + data.count + "</strong> log(s) en base.</span>"
              );
              // Recharger le DataTable après indexation réussie
              if ($.fn.DataTable.isDataTable('#logs-table')) {
                setTimeout(function() {
                  $('#logs-table').DataTable().ajax.reload();
                  $('#upload-feedback').html('');
                }, 2000);
              }
            } else {
              $('#upload-feedback').append(
                "<br><span style='color:red;'>Erreur indexation : " + (data.message || 'inconnue') + "</span>"
              );
            }
          } catch(e) {
            $('#upload-feedback').append(
              "<br><span style='color:red;'>Erreur indexation (format réponse) : " + e + "</span>"
            );
          }
        });
      }
    },
    error: function() {
      $('#upload-feedback').html("Erreur lors de l'upload.");
    }
  });
});

