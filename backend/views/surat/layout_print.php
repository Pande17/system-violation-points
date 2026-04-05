<style>
    @font-face {
        font-family: 'Times New Roman';
        src: local('Times New Roman');
    }
    @page { 
        size: A4; 
        margin: 2cm 2.5cm 2cm 2.5cm; 
    }
    body { 
        font-family: 'Times New Roman', Times, serif; 
        margin: 0; 
        padding: 0; 
        background-color: #f1f1f1; 
        color: #000;
        line-height: 1.5;
    }
    .page { 
        width: 21cm; 
        min-height: 29.7cm; 
        padding: 1.5cm 2cm; 
        margin: 0.5cm auto; 
        background: white; 
        box-shadow: 0 0 10px rgba(0,0,0,0.1); 
        box-sizing: border-box;
        position: relative;
    }
    .header { 
        text-align: center; 
        margin-bottom: 25px; 
    }
    .header img { 
        width: 100%; 
        display: block;
    }
    .content { 
        font-size: 11pt; 
        text-align: justify; 
    }
    .title-section { 
        text-align: center; 
        margin-bottom: 25px; 
    }
    .title-section h3 { 
        text-decoration: underline; 
        margin: 0; 
        text-transform: uppercase; 
        font-size: 14pt; 
    }
    .title-section p { 
        margin: 5px 0; 
        font-weight: bold; 
        font-size: 11pt; 
    }
    .table-details { 
        width: 100%; 
        margin: 15px 0; 
        border-collapse: collapse; 
        font-size: 11pt;
    }
    .table-details td { 
        padding: 3px 0; 
        vertical-align: top; 
    }
    .table-details td:first-child { 
        width: 160px; 
    }
    .table-details td:nth-child(2) { 
        width: 15px; 
    }
    .signature-grid { 
        margin-top: 50px; 
        width: 100%; 
        table-layout: fixed;
    }
    .signature-grid td { 
        text-align: center; 
        vertical-align: top; 
        padding-bottom: 40px;
    }
    .signature-grid .space { 
        height: 80px; 
    }
    .signature-grid .name { 
        font-weight: bold; 
        text-decoration: underline; 
        text-transform: uppercase;
    }
    .mt-4 { margin-top: 1rem; }
    .mb-4 { margin-bottom: 1rem; }
    .ml-8 { margin-left: 2rem; }
    .font-bold { font-weight: bold; }
    .text-center { text-align: center; }
    .underline { text-decoration: underline; }

    @media print {
        body { background: none; margin: 0; padding: 0; }
        .page { margin: 0; box-shadow: none; border: none; width: 100%; min-height: 100vh; padding: 0; }
        .no-print { display: none !important; }
    }
    .no-print { 
        position: fixed; top: 20px; right: 20px; z-index: 999; 
    }
    .print-btn { 
        background: #054030; color: white; border: none; padding: 12px 24px; 
        border-radius: 10px; cursor: pointer; font-weight: 800; 
        text-transform: uppercase; letter-spacing: 1px;
    }
</style>
