import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount } from '@vue/test-utils';
import Swal from 'sweetalert2';
import YourComponent from '@/Pages/Admin/PropertyRequest/ViewPropertyRequest.vue'; 

// Mock the `route` function
global.route = vi.fn((name, id) => `/fake-url/${name}/${id}`);

// Mock FileReader
class MockFileReader {
  constructor() {
    this.onload = null;
  }
  
  readAsDataURL(file) {
    setTimeout(() => {
      if (this.onload) {
        this.result = 'data:image/jpeg;base64,mockedBase64String';
        this.onload({ target: this });
      }
    }, 0);
  }
}

global.FileReader = MockFileReader;

describe('YourComponent Unit Tests', () => {
  let wrapper;

  beforeEach(() => {
    wrapper = mount(YourComponent, {
      props: {
        user: {
          name: 'Test User',
          email: 'test@example.com',
          registration_number: 'REG123',
          image: 'registration.jpg',
          hotels: [
            {
              name: 'Test Hotel',
              accommodation_type: 'Hotel',
              description: 'A nice hotel',
              star: 4,
              price_per_night: 100,
              location: 'Main Street',
              contact_number: '123-456-7890',
              email: 'test@hotel.com',
              website: 'https://testhotel.com',
              district_id: 1,
              province_name: 'Western Province',
              permit_status: 'Pending',
              thumbnail_image: 'hotel.jpg',
              images: [],
            },
          ],
        },
        districts: [{ id: 1, name: 'Colombo' }],
      },
    });

    // Mock Swal.fire to prevent actual popups
    vi.spyOn(Swal, 'fire').mockResolvedValue({ isConfirmed: true });

    // Stub form.post to avoid network requests
    vi.spyOn(wrapper.vm.form, 'post').mockImplementation(() => Promise.resolve());
  });

  it('should correctly compute district name', async () => {
    await wrapper.setProps({
      user: {
        hotels: [{ district_id: 1 }],
      },
      districts: [{ id: 1, name: 'Colombo' }],
    });

    await wrapper.vm.$nextTick();
    expect(wrapper.vm.findDistrictName).toBe('Colombo');
  });

  it('should correctly populate the province input field', async () => {
    await wrapper.setProps({
      user: { hotels: [{ province_name: 'Western Province' }] },
    });

    await wrapper.vm.$forceUpdate();
    await wrapper.vm.$nextTick();
    
    const provinceInput = wrapper.find('input[placeholder="Enter province"]');
    expect(provinceInput.element.value).toBe('Western Province');
  });

  it('should open image modal when clicking on a hotel image', async () => {
    await wrapper.vm.showFullImage('hotel.jpg');
    await wrapper.vm.$forceUpdate(); 
    await wrapper.vm.$nextTick();
    expect(wrapper.vm.isImageModalOpen).toBe(true);
  });


  it('should correctly filter districts', async () => {
    await wrapper.setProps({
      districts: [{ id: 1, name: 'Colombo' }],
      user: { hotels: [{ district_id: 1 }] },
    });

    await wrapper.vm.$forceUpdate();
    await wrapper.vm.$nextTick();
    expect(wrapper.vm.filterValueDistrict).toEqual([{ id: 1, name: 'Colombo' }]);
  });

  it('should handle image upload correctly', async () => {
    // Create a mock file
    const file = new File(['dummy'], 'image.jpg', { type: 'image/jpeg' });
    
    // Set up the mocked form so we can track changes
    wrapper.vm.form.image = null;
    wrapper.vm.imagePreview = null;
    
    // Create a mock event
    const event = { target: { files: [file] } };
    
    // Call the method
    await wrapper.vm.handleImageUpload(event);
    
    // Because FileReader is asynchronous, we need to wait for the next tick
    await wrapper.vm.$nextTick();
    await new Promise(resolve => setTimeout(resolve, 0));
    
    // Now check that imagePreview was updated
    expect(wrapper.vm.imagePreview).not.toBeNull();
    expect(wrapper.vm.form.image).toBe(file);
    expect(wrapper.vm.imagePreview).toBe('data:image/jpeg;base64,mockedBase64String');
  });
});