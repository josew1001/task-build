// filepath: /var/www/html/project/proprli/tests/unit/ManageTask.spec.js
import { shallowMount } from '@vue/test-utils';
import ManageTask from '@/components/tasks/ManageTask.vue';
import axios from 'axios';
import Swal from 'sweetalert2';

jest.mock('axios');
jest.mock('sweetalert2');

describe('ManageTask.vue', () => {
    let wrapper;

    beforeEach(() => {
        wrapper = shallowMount(ManageTask);
    });

    afterEach(() => {
        jest.clearAllMocks();
    });

    it('calls getTasks on mounted', () => {
        const getTasksSpy = jest.spyOn(wrapper.vm, 'getTasks');
        wrapper.vm.$options.mounted[0].call(wrapper.vm);
        expect(getTasksSpy).toHaveBeenCalled();
    });

    it('shows alert when title or description is empty in newTask', async () => {
        const swalSpy = jest.spyOn(Swal, 'fire');
        wrapper.vm.form.title = '';
        wrapper.vm.form.description = '';
        await wrapper.vm.newTask();
        expect(swalSpy).toHaveBeenCalledWith({
            icon: 'warning',
            title: 'Attention!',
            text: 'The title and description must be filled out.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6',
            timer: 5000,
        });
    });
});