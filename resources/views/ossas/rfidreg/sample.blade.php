<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>School ID Card Designer</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .designer-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 30px;
            margin: 20px auto;
        }
        
        .canvas-wrapper {
            background: #e9ecef;
            border-radius: 10px;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 500px;
            position: relative;
        }
        
        #canvas-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            cursor: grab;
        }
        
        #canvas-container:active {
            cursor: grabbing;
        }
        
        .control-panel {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            height: 100%;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
        }
        
        .form-group input {
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s;
        }
        
        .form-group input:focus {
            border-color: #4a90e2;
            box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
        }
        
        .btn-primary {
            background: #4a90e2;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background: #357abd;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74, 144, 226, 0.3);
        }
        
        .btn-success {
            background: #28a745;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }
        
        .btn-danger {
            background: #dc3545;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }
        
        .toolbar {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .toolbar .btn {
            padding: 8px 16px;
            font-size: 14px;
        }
        
        .status-badge {
            background: #28a745;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
        }
        
        .preview-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            border: 2px dashed #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-12">
                <div class="designer-container">
                    <div class="row">
                        <!-- Left: Canvas -->
                        <div class="col-lg-8">
                            <h4 class="mb-3">
                                <i class="fas fa-id-card me-2"></i>ID Card Designer
                                <span class="status-badge ms-2">Drag & Drop Enabled</span>
                            </h4>
                            
                            <!-- Toolbar -->
                            <div class="toolbar">
                                <button class="btn btn-sm btn-outline-primary" onclick="addTextBox()">
                                    <i class="fas fa-font me-1"></i> Add Text Box
                                </button>
                                <button class="btn btn-sm btn-outline-success" onclick="addQRCode()">
                                    <i class="fas fa-qrcode me-1"></i> Add QR Code
                                </button>
                                <button class="btn btn-sm btn-outline-warning" onclick="clearCanvas()">
                                    <i class="fas fa-undo me-1"></i> Reset Design
                                </button>
                                <button class="btn btn-sm btn-outline-info" onclick="exportDesign()">
                                    <i class="fas fa-download me-1"></i> Export JSON
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" onclick="loadDesign()">
                                    <i class="fas fa-upload me-1"></i> Load Design
                                </button>
                            </div>
                            
                            <!-- Canvas Area -->
                            <div class="canvas-wrapper">
                                <div id="canvas-container">
                                    <canvas id="idCardCanvas" width="800" height="500"></canvas>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Tip: Click on any text element to select it, then drag to reposition.
                                    Double-click to edit text.
                                </small>
                            </div>
                        </div>
                        
                        <!-- Right: Form Panel -->
                        <div class="col-lg-4">
                            <div class="control-panel">
                                <h5 class="mb-4">
                                    <i class="fas fa-user-edit me-2"></i>Student Information
                                </h5>
                                
                                <form id="studentForm">
                                    <div class="form-group">
                                        <label for="studentId">Student ID Number</label>
                                        <input type="text" class="form-control" id="studentId" 
                                               placeholder="e.g., 2024-0001" value="2024-0001">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="studentName">Full Name</label>
                                        <input type="text" class="form-control" id="studentName" 
                                               placeholder="e.g., Juan Dela Cruz" value="Juan Dela Cruz">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="program">Program</label>
                                        <input type="text" class="form-control" id="program" 
                                               placeholder="e.g., BSCS" value="Bachelor of Science in Computer Science">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="yearLevel">Year Level</label>
                                        <input type="text" class="form-control" id="yearLevel" 
                                               placeholder="e.g., 3rd Year" value="3rd Year">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="emergencyContact">Emergency Contact</label>
                                        <input type="text" class="form-control" id="emergencyContact" 
                                               placeholder="e.g., Maria Dela Cruz - 09123456789" 
                                               value="Maria Dela Cruz - 09123456789">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="qrCodeData">QR Code Data</label>
                                        <input type="text" class="form-control" id="qrCodeData" 
                                               placeholder="e.g., Student ID: 2024-0001" 
                                               value="Student ID: 2024-0001">
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-primary" onclick="updateCard()">
                                            <i class="fas fa-sync-alt me-2"></i>Update ID Card
                                        </button>
                                        
                                        <button type="button" class="btn btn-success" onclick="generatePDF()">
                                            <i class="fas fa-file-pdf me-2"></i>Generate PDF
                                        </button>
                                        
                                        <button type="button" class="btn btn-danger" onclick="resetFields()">
                                            <i class="fas fa-eraser me-2"></i>Reset Fields
                                        </button>
                                    </div>
                                </form>
                                
                                <!-- Preview Info -->
                                <div class="preview-card mt-3">
                                    <small class="text-muted d-block mb-2">
                                        <i class="fas fa-layer-group me-1"></i>Active Elements:
                                    </small>
                                    <div id="elementInfo">
                                        <span class="badge bg-info">0 elements</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    
    <script>
        // ============================================
        // 1. INITIALIZE FABRIC.JS CANVAS
        // ============================================
        const canvas = new fabric.Canvas('idCardCanvas', {
            width: 800,
            height: 500,
            backgroundColor: 'white',
            selection: true,
            preserveObjectStacking: true
        });

        // ============================================
        // 2. LOAD BACKGROUND IMAGE (Your Photoshop Design)
        // ============================================
        function loadBackground() {
            // Create a placeholder background with gradient and design elements
            // In production, you would load your actual Photoshop design as an image
            const backgroundRect = new fabric.Rect({
                left: 0,
                top: 0,
                width: 800,
                height: 500,
                fill: new fabric.Gradient({
                    type: 'linear',
                    coords: { x1: 0, y1: 0, x2: 0, y2: 500 },
                    colorStops: [
                        { offset: 0, color: '#2c3e50' },
                        { offset: 0.5, color: '#34495e' },
                        { offset: 1, color: '#2c3e50' }
                    ]
                }),
                selectable: false,
                evented: false
            });
            
            // Add a decorative header
            const header = new fabric.Rect({
                left: 0,
                top: 0,
                width: 800,
                height: 80,
                fill: '#3498db',
                selectable: false,
                evented: false
            });
            
            // Add school name
            const schoolName = new fabric.Text('SCHOOL ID CARD', {
                left: 400,
                top: 25,
                fontSize: 28,
                fontWeight: 'bold',
                fill: 'white',
                fontFamily: 'Arial',
                originX: 'center',
                selectable: false,
                evented: false
            });
            
            // Add a decorative line
            const line = new fabric.Line([100, 80, 700, 80], {
                stroke: 'white',
                strokeWidth: 2,
                selectable: false,
                evented: false
            });
            
            // Add placeholder for photo (white box)
            const photoBox = new fabric.Rect({
                left: 50,
                top: 120,
                width: 150,
                height: 180,
                fill: 'white',
                stroke: '#3498db',
                strokeWidth: 3,
                rx: 10,
                ry: 10,
                selectable: false,
                evented: false
            });
            
            // Add photo label
            const photoLabel = new fabric.Text('PHOTO', {
                left: 125,
                top: 200,
                fontSize: 16,
                fill: '#95a5a6',
                fontFamily: 'Arial',
                originX: 'center',
                selectable: false,
                evented: false
            });
            
            // Add all background elements (non-selectable)
            canvas.add(backgroundRect);
            canvas.add(header);
            canvas.add(schoolName);
            canvas.add(line);
            canvas.add(photoBox);
            canvas.add(photoLabel);
            
            // NOW ADD DRAGGABLE TEXT FIELDS (These will be populated from form)
            addDraggableText('studentId', '2024-0001', 280, 140, '#ffffff');
            addDraggableText('studentName', 'Juan Dela Cruz', 280, 190, '#ffffff');
            addDraggableText('program', 'Bachelor of Science in Computer Science', 280, 240, '#ecf0f1');
            addDraggableText('yearLevel', '3rd Year', 280, 290, '#ecf0f1');
            addDraggableText('emergencyContact', 'Maria Dela Cruz - 09123456789', 280, 340, '#bdc3c7');
            
            // Add QR Code placeholder
            addQRCodePlaceholder('Student ID: 2024-0001', 650, 380);
            
            canvas.renderAll();
            updateElementCount();
        }

        // ============================================
        // 3. FUNCTION TO ADD DRAGGABLE TEXT FIELDS
        // ============================================
        function addDraggableText(fieldId, value, left, top, color = '#ffffff') {
            // Check if text already exists and remove it
            const existing = canvas.getObjects().find(obj => obj.fieldId === fieldId);
            if (existing) {
                canvas.remove(existing);
            }
            
            const text = new fabric.Text(value, {
                left: left,
                top: top,
                fontSize: 16,
                fontFamily: 'Arial',
                fill: color,
                fontWeight: 'bold',
                selectable: true,
                hasControls: true,
                hasBorders: true,
                borderColor: '#3498db',
                cornerColor: '#3498db',
                cornerSize: 8,
                fieldId: fieldId,
                name: fieldId,
                type: 'text',
                padding: 10,
                backgroundColor: 'rgba(0,0,0,0.3)',
                cornerStyle: 'circle',
                transparentCorners: false,
                // Add double-click to edit
                objectCaching: false
            });
            
            // Add double-click event to edit text
            text.on('mousedblclick', function() {
                const newText = prompt('Edit text:', this.text);
                if (newText !== null) {
                    this.set('text', newText);
                    canvas.renderAll();
                    // Also update the form field if it matches
                    updateFormField(this.fieldId, newText);
                }
            });
            
            canvas.add(text);
            canvas.renderAll();
            updateElementCount();
            return text;
        }

        // ============================================
        // 4. FUNCTION TO ADD QR CODE
        // ============================================
        function addQRCodePlaceholder(data, left, top) {
            // For demo, create a visual placeholder for QR code
            const qrBackground = new fabric.Rect({
                left: left,
                top: top,
                width: 100,
                height: 100,
                fill: 'white',
                stroke: '#2ecc71',
                strokeWidth: 2,
                rx: 5,
                ry: 5,
                selectable: false,
                evented: false
            });
            
            const qrLabel = new fabric.Text('QR CODE', {
                left: left + 50,
                top: top + 40,
                fontSize: 12,
                fill: '#2ecc71',
                fontFamily: 'Arial',
                originX: 'center',
                selectable: false,
                evented: false
            });
            
            const qrData = new fabric.Text(data, {
                left: left + 50,
                top: top + 65,
                fontSize: 10,
                fill: '#95a5a6',
                fontFamily: 'Arial',
                originX: 'center',
                selectable: false,
                evented: false
            });
            
            canvas.add(qrBackground);
            canvas.add(qrLabel);
            canvas.add(qrData);
            canvas.renderAll();
        }

        // ============================================
        // 5. UPDATE CARD FROM FORM
        // ============================================
        function updateCard() {
            // Get form values
            const studentId = document.getElementById('studentId').value;
            const studentName = document.getElementById('studentName').value;
            const program = document.getElementById('program').value;
            const yearLevel = document.getElementById('yearLevel').value;
            const emergencyContact = document.getElementById('emergencyContact').value;
            const qrData = document.getElementById('qrCodeData').value;
            
            // Update text fields
            updateTextField('studentId', studentId);
            updateTextField('studentName', studentName);
            updateTextField('program', program);
            updateTextField('yearLevel', yearLevel);
            updateTextField('emergencyContact', emergencyContact);
            
            // Update QR code
            updateQRCode(qrData);
            
            // Show feedback
            showNotification('Card updated successfully!', 'success');
        }

        // ============================================
        // 6. HELPER FUNCTIONS
        // ============================================
        function updateTextField(fieldId, value) {
            const textObj = canvas.getObjects().find(obj => obj.fieldId === fieldId);
            if (textObj) {
                textObj.set('text', value);
                canvas.renderAll();
            } else {
                // If text doesn't exist, create it at default position
                const positions = {
                    'studentId': [280, 140, '#ffffff'],
                    'studentName': [280, 190, '#ffffff'],
                    'program': [280, 240, '#ecf0f1'],
                    'yearLevel': [280, 290, '#ecf0f1'],
                    'emergencyContact': [280, 340, '#bdc3c7']
                };
                const pos = positions[fieldId];
                if (pos) {
                    addDraggableText(fieldId, value, pos[0], pos[1], pos[2]);
                }
            }
        }

        function updateQRCode(data) {
            // Remove existing QR code elements
            const qrElements = canvas.getObjects().filter(obj => 
                obj.type === 'rect' && obj.left === 650 && obj.top === 380
            );
            qrElements.forEach(obj => canvas.remove(obj));
            
            // Add new QR code
            addQRCodePlaceholder(data, 650, 380);
            canvas.renderAll();
        }

        function updateFormField(fieldId, value) {
            const formField = document.getElementById(fieldId);
            if (formField) {
                formField.value = value;
            }
        }

        function updateElementCount() {
            const draggableElements = canvas.getObjects().filter(obj => obj.selectable === true);
            document.getElementById('elementInfo').innerHTML = 
                `<span class="badge bg-info">${draggableElements.length} draggable elements</span>`;
        }

        // ============================================
        // 7. TOOLBAR ACTIONS
        // ============================================
        function addTextBox() {
            const text = new fabric.Text('Double-click to edit', {
                left: Math.random() * 400 + 200,
                top: Math.random() * 300 + 100,
                fontSize: 16,
                fill: '#2c3e50',
                selectable: true,
                hasControls: true,
                fieldId: 'custom_' + Date.now(),
                name: 'Custom Text'
            });
            
            text.on('mousedblclick', function() {
                const newText = prompt('Edit text:', this.text);
                if (newText !== null) {
                    this.set('text', newText);
                    canvas.renderAll();
                }
            });
            
            canvas.add(text);
            canvas.renderAll();
            updateElementCount();
            showNotification('New text box added!', 'info');
        }

        function addQRCode() {
            const qrData = document.getElementById('qrCodeData').value;
            addQRCodePlaceholder(qrData, 650, 380);
            showNotification('QR code added!', 'info');
        }

        function clearCanvas() {
            if (confirm('Are you sure you want to reset the design?')) {
                // Remove all selectable objects
                const selectableObjects = canvas.getObjects().filter(obj => obj.selectable === true);
                selectableObjects.forEach(obj => canvas.remove(obj));
                canvas.renderAll();
                updateElementCount();
                showNotification('Design reset!', 'warning');
            }
        }

        function exportDesign() {
            const designData = {
                objects: canvas.toJSON(),
                version: '1.0',
                timestamp: new Date().toISOString()
            };
            
            const blob = new Blob([JSON.stringify(designData, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `id-card-design-${Date.now()}.json`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            
            showNotification('Design exported successfully!', 'success');
        }

        function loadDesign() {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = '.json';
            input.onchange = function(e) {
                const file = e.target.files[0];
                const reader = new FileReader();
                reader.onload = function(event) {
                    try {
                        const designData = JSON.parse(event.target.result);
                        canvas.loadFromJSON(designData.objects, function() {
                            canvas.renderAll();
                            updateElementCount();
                            showNotification('Design loaded successfully!', 'success');
                        });
                    } catch (error) {
                        showNotification('Error loading design: ' + error.message, 'danger');
                    }
                };
                reader.readAsText(file);
            };
            input.click();
        }

        // ============================================
        // 8. PDF GENERATION (Using canvas.toDataURL)
        // ============================================
        function generatePDF() {
            // In production, you would send this to a Laravel endpoint
            // that generates a PDF using DOMPDF or similar
            const dataURL = canvas.toDataURL({
                format: 'png',
                quality: 1
            });
            
            // Download as PNG for demo
            const link = document.createElement('a');
            link.download = `id-card-${Date.now()}.png`;
            link.href = dataURL;
            link.click();
            
            showNotification('Card exported as PNG! (PDF version would use Laravel backend)', 'success');
        }

        // ============================================
        // 9. RESET FIELDS
        // ============================================
        function resetFields() {
            document.getElementById('studentId').value = '';
            document.getElementById('studentName').value = '';
            document.getElementById('program').value = '';
            document.getElementById('yearLevel').value = '';
            document.getElementById('emergencyContact').value = '';
            document.getElementById('qrCodeData').value = '';
            
            // Update card with empty values
            updateCard();
            showNotification('Fields reset!', 'info');
        }

        // ============================================
        // 10. NOTIFICATION SYSTEM
        // ============================================
        function showNotification(message, type = 'info') {
            const colors = {
                success: '#28a745',
                info: '#17a2b8',
                warning: '#ffc107',
                danger: '#dc3545'
            };
            
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 25px;
                background: ${colors[type] || '#6c757d'};
                color: white;
                border-radius: 8px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                z-index: 9999;
                font-weight: 500;
                animation: slideIn 0.3s ease;
            `;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transition = 'opacity 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // ============================================
        // 11. KEYBOARD SHORTCUTS
        // ============================================
        document.addEventListener('keydown', function(e) {
            // Delete key to remove selected object
            if (e.key === 'Delete' || e.key === 'Backspace') {
                const activeObject = canvas.getActiveObject();
                if (activeObject && activeObject.selectable === true) {
                    canvas.remove(activeObject);
                    canvas.renderAll();
                    updateElementCount();
                    showNotification('Element deleted!', 'info');
                }
            }
        });

        // ============================================
        // 12. AUTO-UPDATE ON FORM INPUT
        // ============================================
        // Update card in real-time as user types
        document.querySelectorAll('#studentForm input').forEach(input => {
            input.addEventListener('input', function() {
                // Debounce the update to avoid too many renders
                clearTimeout(this._timer);
                this._timer = setTimeout(() => {
                    updateCard();
                }, 300);
            });
        });

        // ============================================
        // 13. INITIALIZE
        // ============================================
        // Add CSS animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
        `;
        document.head.appendChild(style);

        // Initialize the card
        window.onload = function() {
            loadBackground();
            showNotification('ID Card Designer ready! Drag elements to reposition them.', 'info');
        };
    </script>
</body>
</html>