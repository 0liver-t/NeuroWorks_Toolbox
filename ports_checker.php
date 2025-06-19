<?php
// Ce fichier est chargé dynamiquement dans l'onglet "Journaux" via index.php

if (!defined('XL_LANG')) {
  http_response_code(403);
  exit("Accès direct non autorisé.");
}

// Libellés traduits
$labels = [
  'title' => XL_LANG['port_checker_title'] ?? "Vérificateur d’ouverture de ports",
  'intro' => XL_LANG['port_checker_intro'] ?? "Le test ci-dessous vérifie si certains ports sont ouverts depuis votre navigateur vers le serveur web.",
  'protocol' => XL_LANG['port_checker_protocol'] ?? "Protocole",
  'range'    => XL_LANG['port_checker_range'] ?? "Port(s)",
  'app'      => XL_LANG['port_checker_application'] ?? "Application",
  'comment'  => XL_LANG['port_checker_comment'] ?? "Commentaire",
  'status'   => XL_LANG['port_checker_status'] ?? "Statut",
  'pending'  => XL_LANG['port_checker_pending'] ?? "⏳",
  'open'     => XL_LANG['port_checker_open'] ?? "✅ Ouvert",
  'closed'   => XL_LANG['port_checker_closed'] ?? "❌ Fermé",
  'error'    => XL_LANG['port_checker_error'] ?? "⚠️ Erreur"
];

// Tableau des ports à tester
$portList = [
  ['protocol' => 'tcp', 'range' => '21',   'application' => 'FTP',         'comment' => 'Transfert de fichiers'],
  ['protocol' => 'tcp', 'range' => '22',   'application' => 'SSH',         'comment' => 'Connexion sécurisée'],
  ['protocol' => 'tcp', 'range' => '80',   'application' => 'HTTP',        'comment' => 'Web non sécurisé'],
  ['protocol' => 'tcp', 'range' => '443',  'application' => 'HTTPS',       'comment' => 'Web sécurisé'],
  ['protocol' => 'udp', 'range' => '53',   'application' => 'DNS',         'comment' => 'Résolution de noms'],
  ['protocol' => 'udp', 'range' => '123',  'application' => 'NTP',         'comment' => 'Heure réseau'],
  ['protocol' => 'tcp', 'range' => '3389', 'application' => 'RDP',         'comment' => 'Bureau à distance'],
  ['protocol' => 'udp', 'range' => '5060', 'application' => 'SIP',         'comment' => 'VoIP / téléphonie'],
  ['protocol' => 'tcp', 'range' => '20-21','application' => 'FTP',         'comment' => 'Commande et données FTP'],
  ['protocol' => 'udp', 'range' => '500',  'application' => 'IPSec/IKE',   'comment' => 'VPN sécurité'],
  ['protocol' => 'udp', 'range' => '69',   'application' => 'TFTP',        'comment' => 'Transfert simplifié'],
  ['protocol' => 'tcp', 'range' => '25',   'application' => 'SMTP',        'comment' => 'Envoi de mail'],
];
?>

<div class="port-checker">
  <h2 style="margin-top: 0;">🔌 <?= $labels['title'] ?></h2>
  <p><?= $labels['intro'] ?></p>

  <table id="portTable" class="display" style="width:100%">
    <thead>
      <tr>
        <th><?= $labels['protocol'] ?></th>
        <th><?= $labels['range'] ?></th>
        <th><?= $labels['app'] ?></th>
        <th><?= $labels['comment'] ?></th>
        <th><?= $labels['status'] ?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($portList as $port): ?>
        <tr data-protocol="<?= htmlspecialchars($port['protocol']) ?>"
            data-range="<?= htmlspecialchars($port['range']) ?>">
          <td><?= strtoupper($port['protocol']) ?></td>
          <td><?= htmlspecialchars($port['range']) ?></td>
          <td><?= htmlspecialchars($port['application']) ?></td>
          <td><?= htmlspecialchars($port['comment']) ?></td>
          <td class="status"><?= $labels['pending'] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const rows = document.querySelectorAll("#portTable tbody tr");

    rows.forEach(row => {
      const protocol = row.dataset.protocol;
      const range = row.dataset.range;
      const statusCell = row.querySelector('.status');

      fetch(`test_port.php?protocol=${protocol}&range=${range}`)
        .then(res => res.json())
        .then(data => {
          if (data.open) {
            statusCell.textContent = "<?= $labels['open'] ?>";
            statusCell.style.color = "green";
          } else {
            statusCell.textContent = "<?= $labels['closed'] ?>";
            statusCell.style.color = "red";
          }
        })
        .catch(() => {
          statusCell.textContent = "<?= $labels['error'] ?>";
          statusCell.style.color = "orange";
        });
    });

    if (window.jQuery && $.fn.DataTable) {
      $('#portTable').DataTable({
        paging: false,
        searching: false,
        info: false,
        ordering: false
      });
    }
  });
</script>
