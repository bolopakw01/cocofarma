(function (window, document) {
  'use strict';

  if (window.initBolopaExportPrint) {
    return;
  }

  function toArray(nodeList) {
    return Array.prototype.slice.call(nodeList || []);
  }

  function sanitizeCell(node) {
    if (!node) {
      return '';
    }

    const clone = node.cloneNode(true);
    const removableSelectors = [
      'script',
      'style',
      '.bolopa-tabel-sort-wrap',
      '.bolopa-tabel-sort-icon',
      'img.bolopa-tabel-sort-icon',
      'img.bolopa-tabel-sort-up',
      'img.bolopa-tabel-sort-down',
      '.sr-only'
    ];

    removableSelectors.forEach(function (selector) {
      toArray(clone.querySelectorAll(selector)).forEach(function (el) {
        el.remove();
      });
    });

    const raw = clone.textContent || '';
    return raw
      .replace(/[\u2022\u2023\u25CF\u25A0\u25A1\u25C6\u25C7\u25AA\u25AB\u2219\u29BE\u29BF]/g, ' ')
      .replace(/\s+/g, ' ')
      .trim();
  }

  function isRowVisible(row) {
    if (!row) {
      return false;
    }
    if (row.style && row.style.display === 'none') {
      return false;
    }
    if (typeof row.offsetParent !== 'undefined' && row.offsetParent !== null) {
      return true;
    }
    if (window.getComputedStyle) {
      const computed = window.getComputedStyle(row);
      return computed.display !== 'none' && computed.visibility !== 'hidden' && parseFloat(computed.opacity || '1') !== 0;
    }
    return true;
  }

  function computeSkipIndices(headerRow, config) {
    const skip = new Set();
    const explicitIndices = Array.isArray(config.skipColumnsIndices) ? config.skipColumnsIndices : [];
    explicitIndices.forEach(function (idx) {
      if (typeof idx === 'number' && idx >= 0) {
        skip.add(idx);
      }
    });

    const headerCells = toArray(headerRow ? headerRow.children : []);
    const skipByHeader = (config.skipColumnsByHeader || []).map(function (header) {
      return String(header || '').toLowerCase();
    });

    headerCells.forEach(function (cell, index) {
      const label = sanitizeCell(cell).toLowerCase();
      if (skipByHeader.indexOf(label) !== -1) {
        skip.add(index);
      }
    });

    return skip;
  }

  function buildCsv(table, headerRow, skipIndices) {
    const headerCells = toArray(headerRow.children);
    const headerValues = headerCells.map(function (cell, index) {
      if (skipIndices.has(index) && cell.colSpan <= 1) {
        return null;
      }
      const value = sanitizeCell(cell).replace(/"/g, '""');
      return '"' + value + '"';
    }).filter(function (value) { return value !== null; });

    const csvLines = [];
    if (headerValues.length) {
      csvLines.push(headerValues.join(','));
    }

    const bodyRows = toArray(table.querySelectorAll('tbody tr'));
    const visibleRows = bodyRows.filter(isRowVisible);

    if (!visibleRows.length) {
      throw new Error('Tidak ada data untuk diekspor.');
    }

    visibleRows.forEach(function (row) {
      const cells = toArray(row.children);
      const rowValues = [];
      cells.forEach(function (cell, index) {
        if (skipIndices.has(index) && cell.colSpan <= 1) {
          return;
        }
        const value = sanitizeCell(cell).replace(/"/g, '""');
        rowValues.push('"' + value + '"');
      });
      if (rowValues.length) {
        csvLines.push(rowValues.join(','));
      }
    });

    return csvLines;
  }

  function pruneTableColumns(table, skipIndices, visibleRowIndexes) {
    const sortedSkip = Array.from(skipIndices).sort(function (a, b) { return b - a; });

    function removeColumns(row) {
      const cells = toArray(row.children);
      sortedSkip.forEach(function (columnIndex) {
        const cell = cells[columnIndex];
        if (cell && cell.colSpan <= 1) {
          cell.remove();
        }
      });
    }

    toArray(table.querySelectorAll('thead tr, tfoot tr')).forEach(removeColumns);

    if (Array.isArray(visibleRowIndexes)) {
      const bodyRows = toArray(table.querySelectorAll('tbody tr'));
      bodyRows.forEach(function (row, index) {
        if (visibleRowIndexes.indexOf(index) === -1) {
          row.remove();
        } else {
          removeColumns(row);
        }
      });
    }

    const removableSelectors = [
      'button',
      'a.bolopa-tabel-btn',
      '.bolopa-tabel-sort-wrap',
      '.bolopa-tabel-sort-icon',
      'img.bolopa-tabel-sort-icon',
      'img.bolopa-tabel-sort-up',
      'img.bolopa-tabel-sort-down',
      '.clear-btn',
      '.bolopa-tabel-search-box',
      '.btn'
    ];

    removableSelectors.forEach(function (selector) {
      toArray(table.querySelectorAll(selector)).forEach(function (el) {
        el.remove();
      });
    });

    toArray(table.querySelectorAll('[onclick]')).forEach(function (el) {
      el.removeAttribute('onclick');
    });
  }

  function initBolopaExportPrint(config) {
    var defaults = {
      tableSelector: '#dataTable',
      exportButtonSelector: null,
      printButtonSelector: null,
      filenamePrefix: 'data-export',
      skipColumnsIndices: [],
      skipColumnsByHeader: ['aksi'],
      printedBy: 'Administrator',
      totalLabel: 'Total Data',
      totalSubLabel: 'Sumber data: Sistem Bolopa',
      printBrandTitle: 'Cocofarma — Data',
      printBrandSubtitle: '',
      printNotes: '',
      getTimestamp: function () {
        return new Date().toISOString().replace(/[-:T]/g, '').slice(0, 14);
      },
      notify: null,
      messages: {
        exportSuccess: 'Data berhasil diekspor.',
        exportError: 'Gagal mengekspor data.',
        printInfo: 'Membuka tampilan print...',
        printError: 'Gagal membuka tampilan print.'
      }
    };

    var settings = Object.assign({}, defaults, config || {});
    settings.messages = Object.assign({}, defaults.messages, settings.messages || {});

    var table = document.querySelector(settings.tableSelector);
    if (!table) {
      console.warn('initBolopaExportPrint: tabel tidak ditemukan untuk selector', settings.tableSelector);
    }

    function exportHandler() {
      if (!table) {
        throw new Error('Tabel tidak ditemukan.');
      }

      var headerRow = table.querySelector('thead tr');
      if (!headerRow) {
        throw new Error('Header tabel tidak ditemukan.');
      }

      var skipIndices = computeSkipIndices(headerRow, settings);
      var csvLines = buildCsv(table, headerRow, skipIndices);

      var csvContent = '\uFEFF' + csvLines.join('\n');
      var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
      var timestamp = settings.getTimestamp();
      var filename = settings.filenamePrefix ? (settings.filenamePrefix + '-' + timestamp + '.csv') : ('export-' + timestamp + '.csv');

      var link = document.createElement('a');
      link.href = URL.createObjectURL(blob);
      link.download = filename;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      URL.revokeObjectURL(link.href);
    }

    function printHandler() {
      if (!table) {
        throw new Error('Tabel tidak ditemukan.');
      }

      var headerRow = table.querySelector('thead tr');
      if (!headerRow) {
        throw new Error('Header tabel tidak ditemukan.');
      }

      var bodyRows = toArray(table.querySelectorAll('tbody tr'));
      var visibleRowIndexes = [];
      bodyRows.forEach(function (row, index) {
        if (isRowVisible(row)) {
          visibleRowIndexes.push(index);
        }
      });

      if (!visibleRowIndexes.length) {
        throw new Error('Tidak ada data untuk dicetak.');
      }

      var skipIndices = computeSkipIndices(headerRow, settings);
      var clonedTable = table.cloneNode(true);
      pruneTableColumns(clonedTable, skipIndices, visibleRowIndexes);

      var printWindow = window.open('', '_blank', 'width=1024,height=768');
      if (!printWindow) {
        throw new Error('Popup print diblokir oleh browser.');
      }

      var totalCount = visibleRowIndexes.length;
      var subtitle = settings.printBrandSubtitle ? '<div class="small">' + settings.printBrandSubtitle + '</div>' : '';
      var notes = settings.printNotes ? '<div class="notes">' + settings.printNotes + '</div>' : '';
      var metaRight = "".concat(
        '<div style="text-align:right;">',
        '<div><strong>', settings.totalLabel, ':</strong> ', totalCount, '</div>',
        '<div class="small">', settings.totalSubLabel, '</div>',
        '</div>'
      );
      var metaLeft = "".concat(
        '<div>',
        '<strong>Dicetak oleh:</strong> ', (settings.printedBy || 'Administrator'), '<br>',
        '<span class="small">Tanggal cetak: ', new Date().toLocaleString('id-ID'), '</span>',
        '</div>'
      );

      var styles = "".concat(
        '<style>',
        '@page { size: A4 portrait; margin: 12mm; }',
        'body { font-family: "Poppins", Arial, sans-serif; margin: 0; color: #1f2937; }',
        '.print-shell { padding: 12px 14px; }',
        '.brand { display:flex; align-items:center; gap:12px; margin-bottom:6px; }',
        'h1 { font-size: 16px; margin: 4px 0 8px 0; }',
        '.meta { display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; color:#374151; font-size:12px; }',
        'table { width: 100%; border-collapse: collapse; table-layout: auto; font-size:11px; }',
        'th, td { border: 2px solid #d1d5db; padding: 8px 10px; text-align: left; vertical-align: middle; }',
        'th { background: #f8fafc; font-weight: 700; color:#111827; }',
        'tbody tr:nth-child(even) td { background: #fbfdfe; }',
        '.small { font-size:11px; color:#6b7280; }',
        '.notes { margin-top:12px; font-size:12px; color:#374151; }',
        'td, th { white-space: normal; word-break: break-word; }',
        '@media print {',
        '.no-print { display: none !important; }',
        'table { page-break-inside: auto; }',
        'thead { display: table-header-group; }',
        'tfoot { display: table-footer-group; }',
        'tr { page-break-inside: avoid; page-break-after: auto; }',
        'body { -webkit-print-color-adjust: exact; }',
        '}',
        '</style>'
      );

      printWindow.document.write(
        "<html>" +
          '<head>' +
            '<title>' + (settings.printBrandTitle || 'Data Tabel') + '</title>' +
            styles +
          '</head>' +
          '<body>' +
            '<div class="print-shell">' +
              '<div class="brand">' +
                '<div>' +
                  '<div style="font-weight:700; font-size:16px;">' + (settings.printBrandTitle || 'Data Tabel') + '</div>' +
                  subtitle +
                '</div>' +
              '</div>' +
              '<div class="meta">' +
                metaLeft +
                metaRight +
              '</div>' +
              clonedTable.outerHTML +
              notes +
            '</div>' +
          '</body>' +
        '</html>'
      );

      printWindow.document.close();
      printWindow.focus();
      printWindow.print();
      printWindow.addEventListener('afterprint', function () {
        printWindow.close();
      });
    }

    function bindButton(selector, handler, onSuccess, onError, beforeAction) {
      if (!selector) {
        return;
      }
      var button = document.querySelector(selector);
      if (!button) {
        return;
      }
      button.addEventListener('click', function () {
        try {
          if (typeof beforeAction === 'function') {
            beforeAction();
          }
          handler();
          if (typeof onSuccess === 'function') {
            onSuccess();
          }
        } catch (error) {
          console.error(error);
          if (typeof onError === 'function') {
            onError(error);
          }
        }
      });
    }

    var notify = typeof settings.notify === 'function' ? settings.notify : function () {};

    bindButton(
      settings.exportButtonSelector,
      exportHandler,
      function () { notify(settings.messages.exportSuccess, 'success'); },
      function () { notify(settings.messages.exportError, 'error'); }
    );

    bindButton(
      settings.printButtonSelector,
      printHandler,
      null,
      function () { notify(settings.messages.printError, 'error'); },
      function () { notify(settings.messages.printInfo, 'info'); }
    );

    return {
      export: exportHandler,
      print: printHandler
    };
  }

  window.initBolopaExportPrint = initBolopaExportPrint;
})(window, document);
