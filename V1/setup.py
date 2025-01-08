#  MIT License
# 
#  Copyright (c) 2024 Atrylix
# 
#  Permission is hereby granted, free of charge, to any person obtaining a copy
#  of this software and associated documentation files (the "Software"), to deal
#  in the Software without restriction, including without limitation the rights
#  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
#  copies of the Software, and to permit persons to whom the Software is
#  furnished to do so, subject to the following conditions:
# 
#  The above copyright notice and this permission notice shall be included in all
#  copies or substantial portions of the Software.
# 
#  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
#  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
#  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
#  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
#  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
#  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
#  SOFTWARE.


import os, subprocess, shutil, json
from pathlib import Path

current_directory = str(Path(__file__).parent) + '/'
framework_files = current_directory + 'framework'

testing = False

# Script entry point
def main():
    # Adjust project path for testing
    if testing:
        project_path = '/home/eli/Documents/scripts/php-framework-setup/'
    else:
        project_path = get_project_path()

    project_name = get_project_name()

    if ready_to_create_project(project_path, project_name):
        create_project(project_path, project_name)
    else:
        exit()

    exit_setup()

# Set the parent path of project
def get_project_path():
    # Note: add a trailing slash (/) if there are none, and remove if there are too many
    print('Enter project path (Format /path/to/your/project/ or ~/path/to/your/project/):')
    path = input()

    return path.replace('~/', '/home/' + os.getlogin() + '/', 1)

# Set the project name
def get_project_name():
    # Note: run checks to see if there are any spaces
    print('Enter project name:')
    name = input()

    return name

# Run checks to see weather the project can be created in the provided path
def ready_to_create_project(project_path, project_name):
    if check_if_path_exists(project_path):
        if not check_if_project_exists(project_path, project_name):
            return True
        else:
            print('Project already exists')
            # Note: add option to overwrite project
            return False
    else:
        print('Project parent path doesn\'t exist')
        # Note: add option to create parent
        return False

# The next 2 functions check weather the paths exist
def check_if_path_exists(path):
    return os.path.exists(path)

def check_if_project_exists(project_path, project_name):
    return os.path.exists(project_path + project_name)

# Copy project files
def copy_project_files(path):
    shutil.copytree(framework_files, path)

# Setup compser.json file
def setup_composer(project_path):
    composer_template = get_composer_template()

    composer = set_composer_variables(composer_template)
    
    write_composer(composer, project_path)

    subprocess.run('composer update', shell=True, cwd=project_path)

# Get composer template
def get_composer_template():
    with open('composer.json', 'r') as file:
        composer_template = json.load(file)
    return composer_template

# Write composer to file
def write_composer(composer, project_path):
    with open(project_path + '/composer.json', 'w') as file:
        json.dump(composer, file, indent=4)

# Set composer variables
def set_composer_variables(composer_template):
    composer = composer_template

    # Set project name
    # Note: add format (example/example)
    print('Enter project name (Leave empty to skip)\nFormat (test/project):')
    project_name = input()
    if project_name:
        composer['name'] = project_name

    # Set project description
    print('Enter project description (Leave empty to skip):')
    project_description = input()
    if project_description:
        composer['description'] = project_description

    # Set composer type
    print('Enter composer type (Leave empty for default)\nDefault=project:')
    composer_type = input()
    if composer_type:
        composer['type'] = composer_type
    
    # Set project license
    print('Enter project license (Leave empty for default)\nDefault=MIT:')
    project_license = input()
    if project_license:
        composer['license'] = project_license

    # Set author name
    print('Enter author name (Leave empty to skip):')
    author_name = input()
    if author_name:
        composer['authors'][0]['name'] = author_name

    # Set author email
    # Note: make sure email syntax is correct
    print('Enter author email (Leave empty to skip)\nFormat (email@example.com):')
    author_email = input()
    if author_email:
        composer['authors'][0]['email'] = author_email

    return composer

# Create the project
def create_project(project_path, project_name):
    path = project_path + project_name
    copy_project_files(path)

    while True:
        print('Do you want to setup composer.json? \'y\' or \'n\' (Expert Only)')
        wants_to_setup_composer = input()

        if wants_to_setup_composer.lower() =='y':
            setup_composer(path)
            break
        elif wants_to_setup_composer.lower() == 'n':
            break

# Script exit message
def exit_setup():
    print('\nProjct created!')
    print('Thank you for using my framework :)')
    print('Enjoy!')
    exit(0)

if __name__ == '__main__':
    main()