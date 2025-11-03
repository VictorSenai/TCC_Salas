class KanbanBoard {
    constructor() {
        this.tasks = JSON.parse(localStorage.getItem('kanbanTasks')) || [];
        this.currentDraggedTask = null;
        this.init();
    }

    init() {
        this.bindEvents();
        this.renderTasks();
        this.updateTaskCounts();
    }

    bindEvents() {
        // Modal events
        const modal = document.getElementById('taskModal');
        const addBtn = document.getElementById('addTaskBtn');
        const closeBtn = document.querySelector('.close');
        const cancelBtn = document.getElementById('cancelBtn');
        const taskForm = document.getElementById('taskForm');

        addBtn.addEventListener('click', () => this.openModal());
        closeBtn.addEventListener('click', () => this.closeModal());
        cancelBtn.addEventListener('click', () => this.closeModal());
        
        taskForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.addTask();
        });

        // Click outside modal to close
        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                this.closeModal();
            }
        });
    }

    openModal() {
        document.getElementById('taskModal').style.display = 'block';
        document.getElementById('taskTitle').focus();
    }

    closeModal() {
        document.getElementById('taskModal').style.display = 'none';
        document.getElementById('taskForm').reset();
    }

    addTask() {
        const title = document.getElementById('taskTitle').value.trim();
        const description = document.getElementById('taskDescription').value.trim();
        const priority = document.getElementById('taskPriority').value;

        if (!title) {
            alert('Por favor, insira um título para a tarefa.');
            return;
        }

        const task = {
            id: Date.now().toString(),
            title: title,
            description: description,
            priority: priority,
            column: 'todo',
            createdAt: new Date().toISOString()
        };

        this.tasks.push(task);
        this.saveToLocalStorage();
        this.renderTasks();
        this.updateTaskCounts();
        this.closeModal();

        // Show confirmation
        this.showNotification('Tarefa adicionada com sucesso!');
    }

    deleteTask(taskId) {
        if (confirm('Tem certeza que deseja excluir esta tarefa?')) {
            this.tasks = this.tasks.filter(task => task.id !== taskId);
            this.saveToLocalStorage();
            this.renderTasks();
            this.updateTaskCounts();
            this.showNotification('Tarefa excluída!');
        }
    }

    renderTasks() {
        // Clear all columns
        document.querySelectorAll('.tasks').forEach(column => {
            column.innerHTML = '';
        });

        // Render tasks in their respective columns
        this.tasks.forEach(task => {
            const taskElement = this.createTaskElement(task);
            const column = document.querySelector(`#${task.column} .tasks`);
            if (column) {
                column.appendChild(taskElement);
            }
        });
    }

    createTaskElement(task) {
        const taskDiv = document.createElement('div');
        taskDiv.className = 'task';
        taskDiv.draggable = true;
        taskDiv.dataset.taskId = task.id;

        // Add drag events
        taskDiv.addEventListener('dragstart', (e) => this.dragStart(e, task.id));
        taskDiv.addEventListener('dragend', (e) => this.dragEnd(e));

        const priorityText = {
            'high': 'Alta',
            'medium': 'Média',
            'low': 'Baixa'
        }[task.priority];

        taskDiv.innerHTML = `
            <div class="task-header">
                <div class="task-title">${this.escapeHtml(task.title)}</div>
                <span class="task-priority priority-${task.priority}">
                    ${priorityText}
                </span>
            </div>
            ${task.description ? `<div class="task-description">${this.escapeHtml(task.description)}</div>` : ''}
            <div class="task-actions">
                <button class="delete-btn" onclick="kanban.deleteTask('${task.id}')">
                    Excluir
                </button>
            </div>
        `;

        return taskDiv;
    }

    dragStart(e, taskId) {
        this.currentDraggedTask = taskId;
        e.target.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
    }

    dragEnd(e) {
        e.target.classList.remove('dragging');
        this.currentDraggedTask = null;
    }

    allowDrop(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        e.target.classList.add('dragover');
    }

    drop(e) {
        e.preventDefault();
        e.target.classList.remove('dragover');
        
        if (this.currentDraggedTask) {
            const taskId = this.currentDraggedTask;
            const columnId = e.target.closest('.column').id;
            
            this.moveTaskToColumn(taskId, columnId);
        }
    }

    moveTaskToColumn(taskId, columnId) {
        const task = this.tasks.find(t => t.id === taskId);
        if (task && task.column !== columnId) {
            task.column = columnId;
            this.saveToLocalStorage();
            this.renderTasks();
            this.updateTaskCounts();
            
            // Show notification for column change
            const columnNames = {
                'todo': 'A Fazer',
                'progress': 'Em Progresso',
                'done': 'Concluído'
            };
            this.showNotification(`Tarefa movida para ${columnNames[columnId]}`);
        }
    }

    updateTaskCounts() {
        const columns = ['todo', 'progress', 'done'];
        columns.forEach(columnId => {
            const count = this.tasks.filter(task => task.column === columnId).length;
            const countElement = document.querySelector(`#${columnId} .task-count`);
            if (countElement) {
                countElement.textContent = count;
            }
        });
    }

    saveToLocalStorage() {
        localStorage.setItem('kanbanTasks', JSON.stringify(this.tasks));
    }

    showNotification(message) {
        // Create notification element
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4CAF50;
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1001;
            transition: transform 0.3s;
        `;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
}

// Global functions for HTML attributes
function allowDrop(e) {
    kanban.allowDrop(e);
}

function drop(e) {
    kanban.drop(e);
}

// Initialize Kanban board
const kanban = new KanbanBoard();