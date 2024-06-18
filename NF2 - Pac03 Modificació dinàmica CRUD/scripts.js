$(document).ready(function(){
    loadTables();

    $('#intelForm').on('submit', function(e){
        e.preventDefault();
        let command = $('#commandInput').val();
        handleCommand(command);
    });
});

function loadTables() {
    $.ajax({
        url: 'get_tables.php',
        method: 'GET',
        success: function(response) {
            let tables = JSON.parse(response);
            $('#tablesList').empty();
            tables.forEach(function(table) {
                $('#tablesList').append('<li>' + table + '</li>');
            });
        },
        error: function(xhr, status, error) {
            console.error("Error loading tables: " + error);
        }
    });
}

function handleCommand(command) {
    $.ajax({
        url: 'IntelForm.php',
        method: 'POST',
        data: {command: command},
        success: function(response) {
            $('#resultContainer').html(response);
            loadTables();
        },
        error: function(xhr, status, error) {
            console.error("Error handling command: " + error);
        }
    });
}

$(document).on('click', '#addColumn', function(){
    $('#columnsContainer').append('<div><input type="text" name="columns[]" placeholder="Column Name"><input type="text" name="types[]" placeholder="Data Type"></div>');
});

$(document).on('submit', '#createTableForm', function(e){
    e.preventDefault();
    $.ajax({
        url: 'IntelForm.php',
        method: 'POST',
        data: $(this).serialize() + '&create=true',
        success: function(response) {
            alert(response);
            loadTables();
        },
        error: function(xhr, status, error) {
            console.error("Error creating table: " + error);
        }
    });
});

$(document).on('submit', '#updateForm', function(e){
    e.preventDefault();
    $.ajax({
        url: 'IntelForm.php',
        method: 'POST',
        data: $(this).serialize() + '&update=true',
        success: function(response) {
            alert(response);
            loadTables();
        },
        error: function(xhr, status, error) {
            console.error("Error updating item: " + error);
        }
    });
});

$(document).on('submit', '#deleteForm', function(e){
    e.preventDefault();
    $.ajax({
        url: 'IntelForm.php',
        method: 'POST',
        data: $(this).serialize() + '&delete=true',
        success: function(response) {
            alert(response);
            loadTables();
        },
        error: function(xhr, status, error) {
            console.error("Error deleting item: " + error);
        }
    });
});

$(document).on('submit', '#validationForm', function(e){
    e.preventDefault();
    $.ajax({
        url: 'IntelForm.php',
        method: 'POST',
        data: $(this).serialize() + '&validate=true',
        success: function(response) {
            let data = JSON.parse(response);
            if (data.success) {
                alert(data.message);
            } else {
                alert('Validation errors: ' + JSON.stringify(data.errors));
            }
        },
        error: function(xhr, status, error) {
            console.error("Error validating form: " + error);
        }
    });
});
