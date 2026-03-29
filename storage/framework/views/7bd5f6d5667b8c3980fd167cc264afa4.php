<?php $__env->startSection('title', 'Reverb Diagnostics & Chat Test'); ?>

<?php $__env->startPush('styles-top'); ?>
<style>
    .chat-container {
        height: 500px;
        display: flex;
        flex-direction: column;
        background-color: #f0f2f5;
        border-radius: 15px;
        overflow: hidden;
        border: 1px solid #e0e0e0;
    }
    .chat-header {
        background: #ffffff;
        padding: 15px 20px;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .chat-input-area {
        background: #ffffff;
        padding: 15px 20px;
        border-top: 1px solid #e0e0e0;
    }
    .message-bubble {
        max-width: 75%;
        padding: 10px 15px;
        border-radius: 18px;
        position: relative;
        font-size: 14px;
        line-height: 1.5;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .message-me {
        align-self: flex-end;
        background: linear-gradient(135deg, #6e8efb, #a777e3);
        color: white;
        border-bottom-right-radius: 4px;
    }
    .message-other {
        align-self: flex-start;
        background: #ffffff;
        color: #333;
        border-bottom-left-radius: 4px;
        border: 1px solid #eef0f2;
    }
    .message-info {
        font-size: 11px;
        margin-bottom: 4px;
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }
    .message-me .message-info {
        color: rgba(255, 255, 255, 0.8);
    }
    .message-other .message-info {
        color: #888;
    }
    .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }
    .status-online { background-color: #4caf50; }
    .status-warning { background-color: #ff9800; }
    .status-offline { background-color: #f44336; }
    
    .debug-console {
        background: #1e1e1e;
        color: #00ff00;
        font-family: 'Courier New', Courier, monospace;
        font-size: 12px;
        height: 200px;
        overflow-y: auto;
        padding: 10px;
        border-radius: 8px;
    }
    .debug-entry { margin-bottom: 4px; border-bottom: 1px solid #333; padding-bottom: 2px; }
    .debug-error { color: #ff5252; }
    .debug-info { color: #4fc3f7; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>
<div class="row justify-content-center">
    <div class="col-xl-10 col-lg-12">
        <div class="row g-4">
            <!-- Sidebar for Profile & Diagnostics -->
            <div class="col-md-4">
                <div class="card mb-4 border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0 text-white">Test Mode Selection</h5>
                    </div>
                    <div class="card-body">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <button class="nav-link active mb-2" id="public-test-tab" data-bs-toggle="pill" data-bs-target="#public-test" type="button" role="tab">Public Channel Test</button>
                            <button class="nav-link" id="private-test-tab" data-bs-toggle="pill" data-bs-target="#private-test" type="button" role="tab">Private (Real Chat) Test</button>
                        </div>
                    </div>
                </div>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="public-test" role="tabpanel">
                        <div class="card mb-4">
                            <div class="card-body">
                                <label class="form-label">Your Name</label>
                                <input type="text" class="form-control mb-2" id="userNameInput" placeholder="Enter Your Name">
                                <p class="text-muted small">Public channel: <code>test-channel</code></p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="private-test" role="tabpanel">
                        <div class="card mb-4 border-warning">
                            <div class="card-body">
                                <label class="form-label text-warning fw-bold">Select Active Conversation</label>
                                <select class="form-select mb-3" id="conversationSelect">
                                    <option value="">-- Select Chat to Mirror --</option>
                                    <?php $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($conv->id); ?>">
                                            ID: <?php echo e($conv->id); ?> | <?php echo e($conv->userOne->name); ?> & <?php echo e($conv->userTwo->name); ?> 
                                            (<?php echo e($conv->product->title ?? 'No Product'); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <button class="btn btn-warning w-100" id="joinPrivateBtn">Join & Start Sync</button>
                                <p class="text-muted small mt-2">Joining will listen on <code>private-conversation.{ID}</code></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Reverb Logs</h5>
                        <button class="btn btn-sm btn-link text-muted" onclick="document.getElementById('debugLog').innerHTML = ''">Clear</button>
                    </div>
                    <div class="card-body p-0">
                        <div class="debug-console" id="debugLog">
                            <div class="debug-entry">[INIT] Diagnostics Ready...</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Box -->
            <div class="col-md-8">
                <div class="chat-container shadow-lg">
                    <div class="chat-header">
                        <div>
                            <h5 class="mb-0" id="currentChatTitle">Public Broadcast Room</h5>
                            <small id="connectionStatusWrapper">
                                <span class="status-dot status-offline" id="statusDot"></span>
                                <span id="connectionStatusText">Initializing Reverb...</span>
                            </small>
                        </div>
                        <div id="errorAlert" class="badge bg-danger d-none">Error Detected</div>
                    </div>

                    <div class="chat-messages" id="messageLog">
                        <div class="text-center my-auto" id="noMessages">
                            <h6 class="text-muted">Broadcast activity will appear here.</h6>
                        </div>
                    </div>

                    <div class="chat-input-area">
                        <div class="input-group">
                            <input type="text" class="form-control" id="messageInput" placeholder="Type message...">
                            <button class="btn btn-primary" id="triggerBtn">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts-bottom'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const messageLog = document.getElementById('messageLog');
        const noMessages = document.getElementById('noMessages');
        const triggerBtn = document.getElementById('triggerBtn');
        const messageInput = document.getElementById('messageInput');
        const userNameInput = document.getElementById('userNameInput');
        const statusDot = document.getElementById('statusDot');
        const statusText = document.getElementById('connectionStatusText');
        const debugLog = document.getElementById('debugLog');
        const errorAlert = document.getElementById('errorAlert');
        
        const conversationSelect = document.getElementById('conversationSelect');
        const joinPrivateBtn = document.getElementById('joinPrivateBtn');
        const currentChatTitle = document.getElementById('currentChatTitle');

        let currentMode = 'public'; // 'public' or 'private'
        let currentChannel = null;

        function logDebug(msg, type = 'info') {
            const time = new Date().toLocaleTimeString();
            const entry = document.createElement('div');
            entry.className = `debug-entry debug-${type}`;
            entry.innerHTML = `[${time}] ${msg}`;
            debugLog.appendChild(entry);
            debugLog.scrollTop = debugLog.scrollHeight;
        }

        // Initialize Name
        const savedName = localStorage.getItem('chat_test_name');
        userNameInput.value = savedName || 'Admin_Diagnostic';
        userNameInput.addEventListener('input', () => localStorage.setItem('chat_test_name', userNameInput.value));

        function appendMessage(data, isPrivate = false) {
            if (noMessages) noMessages.style.display = 'none';
            
            const name = isPrivate ? data.sender_name : data.user_name;
            const text = isPrivate ? data.message_text : data.message;
            const time = data.created_at || data.time;

            const isMe = name === userNameInput.value;
            const msgObj = document.createElement('div');
            msgObj.className = `message-bubble ${isMe ? 'message-me' : 'message-other'}`;
            msgObj.innerHTML = `
                <div class="message-info">
                    <span class="fw-bold">${name}</span>
                    <span>${new Date(time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                </div>
                <div>${text}</div>
            `;
            messageLog.appendChild(msgObj);
            messageLog.scrollTop = messageLog.scrollHeight;
        }

        // --- REVERB / ECHO SETUP ---
        if (typeof Echo !== 'undefined') {
            logDebug("SYSTEM: Echo initialized.");

            Echo.connector.pusher.connection.bind('connected', () => {
                statusDot.className = 'status-dot status-online';
                statusText.innerText = 'Connected to Reverb';
                logDebug("REVERB: Connection Established.");
            });

            Echo.connector.pusher.connection.bind('error', (err) => {
                logDebug(`ERROR: WebSocket failed. check .env Reverb host.`, "error");
                errorAlert.classList.remove('d-none');
            });

            // Start with Public Room
            currentChannel = Echo.channel('test-channel');
            currentChannel.listen('.test.event', (data) => {
                const isMe = data.user_name === userNameInput.value;
                if(currentMode === 'public' && !isMe) appendMessage(data);
                logDebug(`RECV: Public message from ${data.user_name}`);
            });

        } else {
            statusText.innerText = 'Echo Not Found';
            logDebug("CRITICAL: Echo undefined.", "error");
        }

        // --- PRIVATE CHANNEL LOGIC ---
        joinPrivateBtn.addEventListener('click', () => {
            const convId = conversationSelect.value;
            if (!convId) return toastr.error('Select a conversation');

            currentMode = 'private';
            currentChatTitle.innerText = `Mirroring Chat #${convId}`;
            messageLog.innerHTML = '<div class="text-center small text-muted mb-3">--- Switched to Private Mirror Mode ---</div>';
            
            logDebug(`INBOX: Fetching message history for #${convId}...`);

            // Fetch Inbox History
            fetch(`/backend/conversation/${convId}/messages`)
                .then(res => res.json())
                .then(messages => {
                    logDebug(`INBOX: Received ${messages.length} messages.`);
                    messages.forEach(msg => {
                        appendMessage({
                            sender_name: msg.sender.name,
                            message_text: msg.message_text,
                            created_at: msg.created_at
                        }, true);
                    });
                    if(messages.length === 0) {
                        messageLog.innerHTML += '<div class="text-center py-3 text-muted">No message history found.</div>';
                    }
                })
                .catch(err => logDebug(`INBOX ERROR: ${err.message}`, "error"));

            logDebug(`AUTH: Requesting private access to conversation.${convId}...`);

            Echo.private(`conversation.${convId}`)
                .listen('.message.sent', (data) => {
                    const isMe = data.sender_name === userNameInput.value;
                    logDebug(`RECV: Private Message from ${data.sender_name}`);
                    if(!isMe) appendMessage(data, true);
                })
                .on('pusher:subscription_succeeded', () => {
                    logDebug(`AUTH: Authorized & Subscribed to conversation.${convId}`, "info");
                    toastr.success('Authorised & Connected to Private Channel');
                })
                .on('pusher:subscription_error', (status) => {
                    logDebug(`AUTH FAILED: Status ${status}. Check routes/channels.php`, "error");
                    toastr.error('Authorization Failed! Check console.');
                });
        });

        // --- MESSAGE SENDING ---
        function sendMessage() {
            const message = messageInput.value.trim();
            if (!message) return;

            const url = currentMode === 'public' 
                ? "<?php echo e(route('backend.trigger_test_event')); ?>" 
                : "<?php echo e(route('backend.trigger_private_event')); ?>";
            
            const payload = currentMode === 'public'
                ? { message, user_name: userNameInput.value }
                : { message, conversation_id: conversationSelect.value };

            messageInput.disabled = true;
            logDebug(`XHR: Broadcasting to ${currentMode} channel...`);

            fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
                body: JSON.stringify(payload)
            })
            .then(async res => {
                const data = await res.json();
                logDebug(`XHR: ${data.status}`);
                
                // Show my message immediately for feedback
                if (currentMode === 'public') {
                    appendMessage({
                        user_name: userNameInput.value,
                        message: message,
                        time: new Date().toISOString()
                    }, false);
                } else {
                    appendMessage({
                        sender_name: userNameInput.value,
                        message_text: message,
                        created_at: new Date().toISOString()
                    }, true);
                }

                messageInput.value = '';
            })
            .catch(err => logDebug(`XHR FAIL: ${err.message}`, "error"))
            .finally(() => {
                messageInput.disabled = false;
                messageInput.focus();
            });
        }

        triggerBtn.addEventListener('click', sendMessage);
        messageInput.addEventListener('keypress', (e) => e.key === 'Enter' && sendMessage());
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\88013\Herd\mastering_laravelRo\resources\views/backend/test-realtime.blade.php ENDPATH**/ ?>