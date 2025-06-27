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
    }
  };
}

function addCompactButton() {
  setTimeout(function() {
    if ($('#toggle-compact').length === 0 && $('.dt-buttons').length) {
      const compactBtn = $(`
        <button id="toggle-compact" type="button" title="${window.lang?.compact_mode || 'Mode compact'}" style="margin-left:0.7em;">
          <i class="fa fa-compress"></i> <span>${window.lang?.compact_mode || 'Mode compact'}</span>
        </button>
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

function showMessage(text, type = "success") {
  const box = document.getElementById("messageBox");
  box.innerHTML = text;
  box.className = "show " + type;
  box.style.display = "block";
  setTimeout(() => {
    box.style.display = "none";
  }, 6000);
}

const CLIENT_SIDE_THRESHOLD = 5000;
let table;
let currentServerSide = null;

function showLoader(show) {
  $('#loader').toggle(!!show);
}
function showError(msg) {
  $('#logs-error').text(msg).show();
}
function hideError() {
  $('#logs-error').hide().text('');
}

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

function initDataTable(serverSide) {
  if (currentServerSide === serverSide && table) return;
  if ($.fn.DataTable.isDataTable('#logs-table')) {
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
  currentServerSide = serverSide;

  $.fn.dataTable.ext.errMode = 'none';

  table = $('#logs-table').DataTable({
    serverSide: serverSide,
    processing: true,
    order: [[0, 'desc']],
    ajax: serverSide
      ? {
          url: 'load_logs.php',
          type: 'GET',
          error: function(xhr) { showLoader(false); showError(window.lang?.error_loading_logs || 'Erreur lors du chargement des logs.'); },
          data: function(d) { d.search = { value: $('#search-input').val() }; },
          beforeSend: function() { showLoader(true); hideError(); },
          complete:   function() { showLoader(false); }
        }
      : {
          url: 'load_logs.php?per_page=250000',
          dataSrc: 'data',
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
		orientation: 'landscape', // optionnel : paysage
		pageSize: 'A4'            // optionnel : A4
	  },
      { extend: 'excelHtml5', text: '<i class="fa fa-file-excel"></i> ' + (window.lang?.export_excel || 'Exporter Excel') },
      { extend: 'print',      text: '<i class="fa fa-print"></i> ' + (window.lang?.print || 'Imprimer') }
    ],
    pageLength: 25,
    language: {
		...getDataTableLang(),
		thousands: '\u202f'
	},
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

function reloadWithSearch() {
  hideError();
  if (!table) return;
  showLoader(true);
  if (currentServerSide) {
    table.ajax.reload(function() { showLoader(false); }, false);
  } else {
    table.ajax.url('load_logs.php?search=' + encodeURIComponent($('#search-input').val()) + '&per_page=250000').load(function() {
      showLoader(false);
    });
  }
}

$(document).ready(function() {
  $.getJSON('load_logs.php?count=1', function(resp) {
    const total = resp?.recordsTotal || 0;
    if (total < CLIENT_SIDE_THRESHOLD) {
      initDataTable(false);
    } else {
      initDataTable(true);
    }
  }).fail(function() {
    initDataTable(true);
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

$('#reload-db-btn').on('click', function() {
  if (!confirm(window.lang?.confirm_reload_db || 'Cette opération va recharger et réindexer la base des logs depuis les fichiers XML.\nContinuer ?')) return;
  showLoader(true);
  $.post('cache_setup.php', { action: 'rebuild' })
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
