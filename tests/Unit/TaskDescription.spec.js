// filepath: /var/www/html/project/proprli/tests/unit/TaskDescription.spec.js
import { shallowMount } from '@vue/test-utils';
import TaskDescription from '@/components/tasks/TaskDescription.vue';
import axios from 'axios';

jest.mock('axios');

describe('TaskDescription.vue', () => {
    let wrapper;

    beforeEach(() => {
        wrapper = shallowMount(TaskDescription, {
            propsData: {
                id: 1,
                user_id: 1
            }
        });
    });

    afterEach(() => {
        jest.clearAllMocks();
    });

    it('fetches task description on mounted', async () => {
        const spy = jest.spyOn(Dropdown, 'getOrCreateInstance').mockImplementation(() => {});
        await wrapper.vm.$nextTick();
        expect(getTaskDescritionSpy).toHaveBeenCalled();
    });

    it('clears form fields in clearForm', () => {
        wrapper.vm.formComment.content = 'Test Comment';
        wrapper.vm.clearForm();
        expect(wrapper.vm.formComment.content).toBe('');
    });
});