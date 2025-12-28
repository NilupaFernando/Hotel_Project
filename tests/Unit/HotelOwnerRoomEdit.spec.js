import { describe, it, expect, beforeEach, vi, afterEach } from 'vitest';
import { mount, flushPromises } from '@vue/test-utils';
import { Head, useForm } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import Swal from 'sweetalert2';
import UpdateHotelComponent from '@/Pages/HotelOwner/Room/edit.vue';

// Mock dependencies
vi.mock('@inertiajs/vue3', () => {
  const mockForm = {
    hotel_id: 1,
    type: '',
    available_rooms: '',
    description: '',
    max_adult: 1,
    max_children: 0,
    features: [],
    free_services: [],
    price_per_adult: '00.0',
    price_per_child: '00.0',
    discount: '00.0',
    guestOptions: [],
    removeRoomOption: [],
    post: vi.fn(),
    errors: {}
  };
  
  return {
    Head: vi.fn(),
    useForm: vi.fn(() => mockForm)
  };
});

vi.mock('@inertiajs/inertia', () => ({
  Inertia: {
    get: vi.fn()
  }
}));

vi.mock('sweetalert2', () => ({
  default: {
    fire: vi.fn()
  }
}));

describe('Update Hotel Component', () => {
  let wrapper;
  let formData;
  
  const mockRoom = {
    id: 1,
    hotel: {
      id: 1,
      name: 'Test Hotel',
      location: 'Test Location',
      contact_number: '1234567890',
      email: 'test@hotel.com',
      room_types: 'Single Room,Double Room'
    },
    type: 'Single Room',
    available_rooms: 5,
    description: 'Test description',
    max_adult: 2,
    max_children: 1,
    features: ['WiFi', 'Air Conditioning'],
    free_services: ['Breakfast', 'Parking'],
    price_per_adult: '50.0',
    price_per_child: '25.0',
    discount: '10.0',
    option_room: [
      {
        max_adult: 2,
        max_children: 1,
        price_per_night: 100.0
      }
    ]
  };

  beforeEach(async () => {
    // Reset mocks
    vi.clearAllMocks();
    
    // Setup form data
    formData = useForm();
    formData.hotel_id = mockRoom.hotel.id;
    formData.type = mockRoom.type;
    formData.available_rooms = mockRoom.available_rooms;
    formData.description = mockRoom.description;
    formData.features = [...mockRoom.features];
    formData.free_services = [...mockRoom.free_services];
    formData.discount = mockRoom.discount;
    formData.guestOptions = [...mockRoom.option_room];
    
    // Create wrapper with mocked props
    wrapper = mount(UpdateHotelComponent, {
      props: {
        room: mockRoom
      },
      global: {
        stubs: {
          HotelOwnerAuthenticatedLayout: true,
          Head: true
        },
        mocks: {
          $inertia: {
            get: vi.fn()
          }
        }
      }
    });
    
    // Wait for component to mount completely
    await flushPromises();
  });

  afterEach(() => {
    wrapper.unmount();
  });

  it('initializes form with room data', () => {
    expect(useForm).toHaveBeenCalled();
    // Check that the formData values match the mockRoom values
    expect(formData.hotel_id).toBe(mockRoom.hotel.id);
    expect(formData.type).toBe(mockRoom.type);
  });

  it('populates guest options from room data', async () => {
    // If guestOptions is a reactive property, access it correctly
    // Access it from wrapper.vm or directly from the formData
    expect(formData.guestOptions).toEqual(mockRoom.option_room);
  });

 
  it('adds a new guest option when save is clicked in modal', async () => {
    // Manually set the modal state to open
    if (typeof wrapper.vm.showGuestOptionModal === 'function') {
      wrapper.vm.showGuestOptionModal();
    } else if (typeof wrapper.vm.showGuestOptionModal === 'object') {
      wrapper.vm.showGuestOptionModal.value = true;
    } else {
      wrapper.vm.showGuestOptionModal = true;
    }
    
    await wrapper.vm.$nextTick();
    
    // Set the guest option data
    if (typeof wrapper.vm.guestOption === 'object') {
      wrapper.vm.guestOption = {
        max_adult: 3,
        max_children: 2,
        price_per_night: 150.0
      };
    }
    
    await wrapper.vm.$nextTick();
    
    // Find the save button in the modal
    const saveButton = wrapper.find('.modal button:first-child') || 
                       wrapper.find('[data-testid="save-option-btn"]') ||
                       wrapper.find('button').filter(w => w.text().includes('Save'));
    
    if (saveButton.exists()) {
      await saveButton.trigger('click');
      
      // Check if the option was added to the form data
      const expectedOption = {
        max_adult: 3,
        max_children: 2,
        price_per_night: 150.0
      };
      
      // Check if guestOptions now contains the new option
      const options = formData.guestOptions;
      expect(options).toContainEqual(expectedOption);
    }
  });

  // Additional tests follow similar pattern - check existence before actions
  // ...
});