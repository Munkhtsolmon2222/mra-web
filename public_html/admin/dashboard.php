<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

$pdo = getDB();
$categories = getCategories();
$csrfToken = generateCSRFToken();

// Get all participants with vote counts
$stmt = $pdo->query("
    SELECT p.*, c.name as category_name, COUNT(v.id) as vote_count
    FROM participants p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN votes v ON p.id = v.participant_id
    GROUP BY p.id
    ORDER BY c.id ASC, p.name ASC
");
$allParticipants = $stmt->fetchAll();

// Group participants by category
$participantsByCategory = [];
foreach ($allParticipants as $participant) {
    $catId = $participant['category_id'];
    if (!isset($participantsByCategory[$catId])) {
        $participantsByCategory[$catId] = [
            'category' => $participant['category_name'],
            'participants' => []
        ];
    }
    $participantsByCategory[$catId]['participants'][] = $participant;
}
?>
<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MRA Awards 2025</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: #f9fafb;
            color: #1f2937;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
        }
        .header {
            background: linear-gradient(135deg, #0a1c44 0%, #0a1c44 100%);
            color: white;
            padding: 1.5rem 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }
        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-primary {
            background: #0a1c44;
            color: white;
        }
        .btn-primary:hover {
            background: #0d2555;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(10, 28, 68, 0.3);
        }
        .btn-danger {
            background: #dc2626;
            color: white;
        }
        .btn-danger:hover {
            background: #b91c1c;
        }
        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }
        .btn-secondary:hover {
            background: #d1d5db;
        }
        .participant-card {
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        .participant-card:hover {
            border-color: #0a1c44;
            box-shadow: 0 4px 12px rgba(10, 28, 68, 0.1);
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal.active {
            display: flex;
        }
        .modal-content {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }
        .logo-preview {
            max-width: 150px;
            max-height: 150px;
            object-fit: contain;
            border-radius: 0.5rem;
            border: 2px solid #e5e7eb;
        }
        input, select, textarea {
            width: 100%;
            padding: 0.625rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            font-size: 1rem;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #0a1c44;
        }
        .vote-badge {
            background: #0a1c44;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        .notification {
            position: fixed;
            top: 2rem;
            right: 2rem;
            padding: 1rem 2rem;
            border-radius: 0.75rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            z-index: 2000;
            opacity: 0;
            transform: translateX(400px);
            transition: all 0.3s ease;
        }
        .notification.show {
            opacity: 1;
            transform: translateX(0);
        }
        .notification.success {
            background: #10b981;
            color: white;
        }
        .notification.error {
            background: #ef4444;
            color: white;
        }
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">MRA Awards 2025 - Admin Dashboard</h1>
            <div class="flex gap-4 items-center">
                <span class="text-sm opacity-90">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <a href="logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Results Export Section -->
        <div class="card mb-6" style="background: linear-gradient(135deg, #0a1c44 0%, #0d2555 100%); color: white;">
            <h2 class="text-2xl font-bold mb-4">Санал асуулгын үр дүн</h2>
            <p class="mb-4 opacity-90">Санал асуулга дууссаны дараа үр дүнг татаж авах боломжтой.</p>
            <a href="../api/admin/export-results.php" class="btn" style="background: white; color: #0a1c44;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Үр дүнг татаж авах (CSV)
            </a>
        </div>

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-900">Participants Management</h2>
            <button onclick="openAddModal()" class="btn btn-primary">+ Оролцогч нэмэх</button>
        </div>

        <?php foreach ($categories as $category): ?>
            <?php 
            $catParticipants = $participantsByCategory[$category['id']]['participants'] ?? [];
            ?>
            <div class="card">
                <h3 class="text-xl font-bold text-gray-900 mb-4" style="color: #0a1c44;">
                    <?php echo htmlspecialchars($category['name']); ?>
                </h3>
                
                <?php if (empty($catParticipants)): ?>
                    <p class="text-gray-500 italic">Энэ төрөлд оролцогч байхгүй байна.</p>
                <?php else: ?>
                    <div class="grid gap-4">
                        <?php foreach ($catParticipants as $participant): ?>
                            <div class="participant-card">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4 flex-1">
                                        <?php if ($participant['logo_path']): ?>
                                            <img src="../<?php echo htmlspecialchars($participant['logo_path']); ?>" 
                                                 alt="<?php echo htmlspecialchars($participant['name']); ?>" 
                                                 class="logo-preview">
                                        <?php else: ?>
                                            <div class="logo-preview bg-gray-100 flex items-center justify-center text-gray-400">
                                                No Logo
                                            </div>
                                        <?php endif; ?>
                                        <div class="flex-1">
                                            <h4 class="font-bold text-lg"><?php echo htmlspecialchars($participant['name']); ?></h4>
                                            <?php if ($participant['description']): ?>
                                                <p class="text-gray-600 text-sm mt-1"><?php echo htmlspecialchars($participant['description']); ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <span class="vote-badge"><?php echo $participant['vote_count']; ?> санал</span>
                                            <button 
                                                onclick="openEditModal(this)" 
                                                data-participant='<?php echo htmlspecialchars(json_encode($participant), ENT_QUOTES, 'UTF-8'); ?>'
                                                class="btn btn-secondary">Засах</button>
                                            <button onclick="deleteParticipant(<?php echo $participant['id']; ?>)" 
                                                    class="btn btn-danger">Устгах</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Notification -->
    <div id="notification" class="notification"></div>

    <!-- Add/Edit Modal -->
    <div id="participantModal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 id="modalTitle" class="text-2xl font-bold" style="color: #0a1c44;">Оролцогч нэмэх</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>
            
            <form id="participantForm" onsubmit="saveParticipant(event)">
                <input type="hidden" id="participant_id" name="participant_id" value="">
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Номинаци *</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">Номинацийг сонгоно уу</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Нэр *</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <!-- <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Тайлбар</label>
                    <textarea id="description" name="description" rows="3"></textarea>
                </div> -->
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Logo</label>
                    <input type="file" id="logo_file" accept="image/jpeg,image/png,image/webp" onchange="uploadLogo(event)">
                    <input type="hidden" id="logo_path" name="logo_path" value="">
                    <div id="logoPreview" class="mt-2"></div>
                </div>
                
                <div class="flex gap-4">
                    <button type="submit" class="btn btn-primary flex-1">Save</button>
                    <button type="button" onclick="closeModal()" class="btn btn-secondary">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const csrfToken = '<?php echo $csrfToken; ?>';
        let currentLogoPath = '';

        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Оролцогч нэмэх';
            document.getElementById('participantForm').reset();
            document.getElementById('participant_id').value = '';
            document.getElementById('logo_path').value = '';
            document.getElementById('logoPreview').innerHTML = '';
            currentLogoPath = '';
            document.getElementById('participantModal').classList.add('active');
        }

        function openEditModal(button) {
            // Get participant data from data attribute
            const participantData = button.getAttribute('data-participant');
            if (!participantData) {
                console.error('No participant data found');
                return;
            }
            
            let participant;
            try {
                participant = JSON.parse(participantData);
            } catch (e) {
                console.error('Error parsing participant data:', e);
                showNotification('Алдаа: Оролцогчийн мэдээлэл уншихад алдаа гарлаа', 'error');
                return;
            }
            
            document.getElementById('modalTitle').textContent = 'Оролцогч засах';
            document.getElementById('participant_id').value = participant.id;
            document.getElementById('category_id').value = participant.category_id;
            document.getElementById('name').value = participant.name;
            
            // Set description only if the field exists
            const descriptionField = document.getElementById('description');
            if (descriptionField) {
                descriptionField.value = participant.description || '';
            }
            
            document.getElementById('logo_path').value = participant.logo_path || '';
            currentLogoPath = participant.logo_path || '';
            
            const preview = document.getElementById('logoPreview');
            if (participant.logo_path) {
                preview.innerHTML = `<img src="../${participant.logo_path}" class="logo-preview" alt="Logo">`;
            } else {
                preview.innerHTML = '';
            }
            
            document.getElementById('participantModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('participantModal').classList.remove('active');
        }

        async function uploadLogo(event) {
            const file = event.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('logo', file);

            const preview = document.getElementById('logoPreview');
            preview.innerHTML = '<div class="text-gray-500">Uploading...</div>';

            try {
                const response = await fetch('../api/admin/upload-logo.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    document.getElementById('logo_path').value = result.path;
                    currentLogoPath = result.path;
                    preview.innerHTML = `<img src="../${result.path}" class="logo-preview" alt="Logo">`;
                    showNotification('Logo uploaded successfully!', 'success');
                } else {
                    preview.innerHTML = '';
                    showNotification('Error: ' + result.error, 'error');
                }
            } catch (error) {
                preview.innerHTML = '';
                showNotification('Error uploading logo: ' + error.message, 'error');
            }
        }

        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.className = `notification ${type}`;
            notification.classList.add('show');
            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }

        async function saveParticipant(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            const participantId = formData.get('participant_id');
            const url = participantId 
                ? '../api/admin/update-participant.php'
                : '../api/admin/add-participant.php';

            form.classList.add('loading');

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    showNotification('Participant saved successfully!', 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showNotification('Error: ' + result.error, 'error');
                    form.classList.remove('loading');
                }
            } catch (error) {
                showNotification('Error saving participant: ' + error.message, 'error');
                form.classList.remove('loading');
            }
        }
//te
        async function deleteParticipant(id) {
            if (!confirm('Та энэ оролцогчийг устгахдаа итгэлтэй байна уу? Ингэснээр уг оролцогчийн бүх санал мөн устах болно.')) {
                return;
            }

            const formData = new FormData();
            formData.append('participant_id', id);
            formData.append('csrf_token', csrfToken);

            try {
                const response = await fetch('../api/admin/delete-participant.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    showNotification('Participant deleted successfully!', 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showNotification('Error: ' + result.error, 'error');
                }
            } catch (error) {
                showNotification('Error deleting participant: ' + error.message, 'error');
            }
        }

        // Close modal on outside click
        document.getElementById('participantModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>

