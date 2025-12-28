import { mount } from '@vue/test-utils';
import PropertyRequests from '@/Pages/Admin/PropertyRequest/PropertyRequest.vue'; 
import { vi, describe, test, expect, beforeEach } from 'vitest';
import { router } from '@inertiajs/vue3';

// Mock Vue and related libraries
vi.mock('vue', async () => {
  const actual = await vi.importActual('vue');
  return {
    ...actual,
  };
});

// Mock all external dependencies
vi.mock('@inertiajs/vue3', () => {
  return {
    router: {
      get: vi.fn(),
      post: vi.fn(),
      put: vi.fn(),
      delete: vi.fn()
    },
    Link: vi.fn(),
    Head: vi.fn()
  };
});

// Mock AOS
vi.mock('aos', () => {
  return {
    default: {
      init: vi.fn()
    }
  };
});

// Mock date-fns
vi.mock('date-fns', () => {
  return {
    format: vi.fn((date, formatStr) => {
      const d = new Date(date);
      const month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'][d.getMonth()];
      const day = String(d.getDate()).padStart(2, '0');
      const year = d.getFullYear();
      return `${month} ${day}, ${year}`;
    })
  };
});

// Stub layout component
const AdminAuthenticatedLayoutStub = {
  template: '<div><slot /></div>'
};

describe('PropertyRequests Component', () => {
  let wrapper;

  const mockHotelOwners = [
    { id: 1, name: 'Hotel A', permit_status: 'active' },
    { id: 2, name: 'Hotel B', permit_status: 'pending' },
    { id: 3, name: 'Hotel C', permit_status: 'pending' }
  ];

  const mockPropertyRequests = [
    { 
      id: 1, 
      name: 'Request A', 
      permit_status: 'pending', 
      hotels: [{ name: 'Hotel A', location: 'City X', created_at: '2024-04-01' }] 
    },
    { 
      id: 2, 
      name: 'Request B', 
      permit_status: 'active', 
      hotels: [{ name: 'Hotel B', location: 'City Y', created_at: '2024-03-15' }] 
    }
  ];

  beforeEach(() => {
    vi.clearAllMocks();
    
    global.route = vi.fn((name, params) => {
      if (name === 'admin.propertyReqView') {
        return `/admin/property-request/${params.id}`;
      }
      return `/mock-route-to-${name}`;
    });
    
    wrapper = mount(PropertyRequests, {
      props: {
        hotelOwner: mockHotelOwners,
        propertyRequest: mockPropertyRequests
      },
      global: {
        stubs: {
          AdminAuthenticatedLayout: AdminAuthenticatedLayoutStub,
          svg: true
        },
        mocks: {
          route: global.route
        }
      },
      shallow: false
    });
  });

  test('renders the component', () => {
    expect(wrapper.exists()).toBe(true);
  });

  test('renders the Property Requests title', () => {
    const title = wrapper.find('.text-3xl');
    expect(title.exists()).toBe(true);
    expect(title.text()).toBe('Property Requests');
  });

  test('calculates total requests correctly', () => {
    expect(wrapper.vm.lengthOfAllHotels).toBe(3);
  });

  test('calculates pending hotels correctly', () => {
    expect(wrapper.vm.lengthOfPendingHotels).toBe(2);
  });

  test('calculates approved hotels correctly', () => {
    expect(wrapper.vm.lengthOfApprovedHotels).toBe(1);
  });

  // ✅ Fixed test
  test('renders property request elements', () => {
    const heading = wrapper.find('h2');
    expect(heading.exists()).toBe(true);
    expect(heading.text()).toBe('Recent Requests');
    
    const hotelElements = wrapper.findAll('.text-lg');
    const hotelTexts = hotelElements.map(el => el.text());
    expect(hotelTexts.some(text => text.includes('Hotel A'))).toBe(true);
  });

  test('shows "No Property Requests" message when list is empty', async () => {
    await wrapper.setProps({ propertyRequest: [] });
    expect(wrapper.text()).toContain('No Property Requests');
  });

  test('navigates to property request details page when clicking details button', async () => {
    const toggleDetailsSpy = vi.spyOn(wrapper.vm, 'toggleDetails');
    wrapper.vm.toggleDetails(1);
    expect(toggleDetailsSpy).toHaveBeenCalledWith(1);
    expect(router.get).toHaveBeenCalledWith('/admin/property-request/1');
  });

  test('formats date correctly', () => {
    const formattedDate = wrapper.vm.formatDate('2024-04-01');
    expect(formattedDate).toBe('April 01, 2024');
  });
});
