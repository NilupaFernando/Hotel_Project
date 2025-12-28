import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount } from '@vue/test-utils';
import { nextTick, h } from 'vue';
import CreateHotel from '@/Pages/HotelOwner/Hotel/Create.vue';

// Mock dependencies
vi.mock('@inertiajs/vue3', () => ({
  Head: { render: () => ({}) },
  Link: { render: () => ({}) },
  router: { get: vi.fn() },
  useForm: vi.fn().mockImplementation((initialValues) => ({
    ...initialValues,
    errors: {},
    post: vi.fn(),
  })),
}));

vi.mock('@inertiajs/inertia', () => ({
  Inertia: { visit: vi.fn() },
}));

// Mock Layout Component
vi.mock('@/Layouts/HotelOwner/HotelOwnerAuthenticatedLayout.vue', () => ({
  default: {
    name: 'HotelOwnerAuthenticatedLayout',
    render() {
      return h('div', {}, [
        h('div', { id: 'header' }, this.$slots.header?.()),
        h('div', { id: 'default' }, this.$slots.default?.()),
      ]);
    },
  },
}));

// Global mocks
global.route = vi.fn((name) => `/${name}`);
global.URL.createObjectURL = vi.fn((file) => `mock-url-for-${file.name}`);
global.alert = vi.fn();

describe('Hotel Create Component Tests', () => {
  let wrapper;
  const mockDistricts = [
    { id: 1, name: 'District 1', province: { province_name: 'Province 1' } },
    { id: 2, name: 'District 2', province: { province_name: 'Province 2' } },
  ];

  beforeEach(() => {
    vi.clearAllMocks();
    wrapper = mount(CreateHotel, {
      props: { districts: mockDistricts },
      global: { stubs: { HotelOwnerAuthenticatedLayout: true, Head: true } },
    });
  });

  it('initializes with correct default values', () => {
    expect(wrapper.vm.form.name).toBe('');
    expect(wrapper.vm.form.description).toBe('');
    expect(wrapper.vm.form.check_in_time).toBe('14:00');
    expect(wrapper.vm.form.check_out_time).toBe('11:00');
    expect(wrapper.vm.form.category).toBe('All');
    expect(wrapper.vm.form.images).toEqual([]);
  });

  it('handles thumbnail image upload', async () => {
    const file = new File(['dummy content'], 'test-thumbnail.png', { type: 'image/png' });
    global.FileReader = function () {
      this.readAsDataURL = vi.fn();
      this.onload = vi.fn();
      this.result = 'data:image/png;base64,mockBase64Data';
    };

    const event = { target: { files: [file] } };
    wrapper.vm.handleThumbImage(event);
    expect(wrapper.vm.form.thumbnail_image).toBe(file);
  });

  it('handles adding images up to the limit', async () => {
    for (let i = 1; i <= 8; i++) {
      const file = new File([`content${i}`], `image${i}.png`, { type: 'image/png' });
      wrapper.vm.handleImageChangetest({ target: { files: [file] } });
    }

    expect(wrapper.vm.form.images.length).toBe(8);

    const extraFile = new File(['extra'], 'extra.png', { type: 'image/png' });
    wrapper.vm.handleImageChangetest({ target: { files: [extraFile] } });
    expect(wrapper.vm.form.images.length).toBe(8);
    expect(global.alert).toHaveBeenCalledWith('only you can upload 8 images');
  });

  it('removes images correctly', async () => {
    wrapper.vm.form.images = [
      { file: new File(['content1'], 'image1.png'), preview: 'url1' },
      { file: new File(['content2'], 'image2.png'), preview: 'url2' },
      { url: 'stored-image-url', preview: 'url3' },
    ];

    wrapper.vm.removeImage(1);
    expect(wrapper.vm.form.images.length).toBe(2);
    expect(wrapper.vm.form.images[1].url).toBe('stored-image-url');

    wrapper.vm.removeImage(1);
    expect(wrapper.vm.form.images.length).toBe(1);
    expect(wrapper.vm.removedImages).toContain('stored-image-url');
  });

  it('prepares form data correctly when saving', async () => {
    wrapper.vm.form.images = [
      { file: new File(['content1'], 'image1.png'), preview: 'url1' },
      { url: 'stored-image-url', preview: 'url3' },
    ];
    wrapper.vm.form.post = vi.fn();

    wrapper.vm.saveHotel();
    expect(wrapper.vm.form.photo.length).toBe(2);
    expect(wrapper.vm.form.photo[0]).toBeInstanceOf(File);
    expect(wrapper.vm.form.photo[1]).toBe('stored-image-url');
    expect(wrapper.vm.form.post).toHaveBeenCalled();
  });

  it('handles form submission success', async () => {
    wrapper.vm.form.post = vi.fn((route, options) => options.onSuccess());

    wrapper.vm.saveHotel();
    expect(global.alert).toHaveBeenCalledWith('Hotel Save Successfully');
  });

  it('handles form submission errors', async () => {
    wrapper.vm.form.errors = { email: 'Invalid email format', latitude: 'Latitude is required' };
    wrapper.vm.form.post = vi.fn();

    wrapper.vm.saveHotel();
    expect(wrapper.vm.form.errors.email).toBe('Invalid email format');
    expect(wrapper.vm.form.errors.latitude).toBe('Latitude is required');
  });
});
