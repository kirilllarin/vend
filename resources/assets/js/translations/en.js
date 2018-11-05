export default {
    message: {
        hello: 'Hello world'
    },
    auth: {
        logout: "Logout"
    },
    projects: {
        index: 'Projects',
        create: 'Create project',
        edit: 'Edit project',
        columns: 'Columns',
        members: 'Members',
        dueDate: 'Due date',
        archiveColumn: 'Archive',
        empty: 'Add your first project!',
        emptyArchive: 'No archived projects',
        archive: 'Archive',
        active: 'Active',
        isArchive: 'Archive project',
        completedColumn: '(Completed)',
        toggleArchive: 'Toggle archive columns',
        form: {
            title: 'Title',
            dueDate: 'Due date',
            addColumn: 'Add column',
            columnTitle: 'Column title'
        }
    },
    cards: {
        edit: 'Edit card',
        create: 'Create card',
        comments: 'Comments',
        logs: 'Logs',
        tasks: 'Tasks',
        files: 'Files',
        form: {
            title: 'Title',
            assignedTo: 'Assign to',
            dueDate: 'Due date'
        }
    },
    tasks: {
        empty: 'No tasks',
        create: 'Add task'
    },
    comments: {
        create: 'Send',
        placeholder: 'Your comment'
    },
    users: {
        index: 'Users',
        create: 'Create user',
        uploadFile: 'Upload avatar',
        edit: 'Edit user',
        role: 'Role',
        roles: {
            admin: 'Admin',
            manager: 'Manager',
            editor: 'Editor',
            client: 'Client'
        },
        form: {
            name: 'Name',
            email: 'E-mail',
            role: 'Role',
            changePassword: 'Change password',
            password: 'Password',
            passwordConfirmation: 'Confirm password'
        },
        filter: {
            noFilter: 'All',
            onlyAdmin: 'Only admins',
            onlyEditor: 'Only editors',
            onlyClient: 'Only clients',
        }
    },
    logs: {
        index: 'Logs',
        tracked: 'Tracked',
        entries: 'Entries',
        length: 'Length (seconds)',
        date: 'Date',
        empty: 'No logs tracked',
        startTimer: 'Start timer',
        stopTimer: 'Stop timer',
    },
    topics: {
        index: 'Discussions',
        events: 'Events',
        messages: 'Messages',
        files: 'Files',
        create: 'New topic',
        edit: 'Edit topic',
        unread: 'Unread',
        read: 'Read',
        empty: 'No discussions',
        form: {
            title: 'Title',
            members: 'Topic users'
        }
    },
    events: {
        index: 'Agenda',
        calendar: 'Calendar',
        agenda: 'Agenda',
        attach: 'Attach event',
        empty: 'No events',
        title: 'Title',
        start: 'Start',
        time: 'Time',
        end: 'End',
        location: 'Location',
        clearAttachments: 'Clear events'
    },
    messages: {
        create: 'New message',
        edit: 'Edit message',
        form: {
            message: 'Your message...'
        }
    },
    files: {
        index: 'Files',
        empty: 'No files'
    },
    notifications: {
        title: 'Notifications',
        markAsRead: 'Mark as read',
        clear: 'Clear notifications',
        empty: 'No ',
        messages: {
            card_updated: '{actor} updated {title} card',
            message_created: '{actor} created a message in {title} topic',
            message_updated: '{actor} updated a message in {title} topic',
            comment_created: '{actor} commented on {title} card',
        }
    },
    favorites: {
        title: 'Favorites',
        empty: 'No favorites'
    },
    common: {
        search: 'Search',
        edit: 'Edit',
        delete: 'Delete',
        save: 'Save',
        cancel: 'Cancel',
        clear: 'Clear'
    },
    filter: {
        none: 'None',
        clear: 'Clear filter',
        index: 'Filter',
        assignedTo: 'Assigned to',
        dueDate: 'Due date',
        thisWeek: 'This week',
        nextWeek: 'Next week',
        thisMonth: 'This month',
        nextMonth: 'Next month',
    },
    uploader: {
        attach: 'Attach files'
    }
}