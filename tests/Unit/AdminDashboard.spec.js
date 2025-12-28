import { describe, it, expect, vi } from 'vitest';
import { mount } from '@vue/test-utils';
import Dashboard from '@/Pages/Admin/Dashboard/Dashboard.vue';

// Mock the route function
vi.mock('ziggy-js', () => ({
  route: vi.fn().mockImplementation((name) => ({
    current: (routeName) => routeName === name,
  })),
}));

// Define a mock $page object
const mockPage = {
  props: {
    auth: {
      user: {
        name: 'Test User',
      },
    },
  },
};

describe('Dashboard.vue', () => {
  it('renders props correctly', () => {
    const wrapper = mount(Dashboard, {
      props: {
        allUsers: 100,
        hotels: 50,
        superAdminPercentage: 20,
        hotelOwnerPercentage: 30,
        userPercentage: 50,
        accommodationPercentages: {
          Hotel: 40,
          Apartment: 30,
          Villa: 20,
          Motel: 10,
        },
        PendingPropertyRequests: 10,
        ApprovedPropertyRequests: 70,
        RejectedPropertyRequests: 15,
        hotelOwnerCount: 5,
        order: 200,
        bookings: 150,
      },
      global: {
        config: {
          globalProperties: {
            $page: mockPage,
          },
        },
      },
    });

    expect(wrapper.props('allUsers')).toBe(100);
    expect(wrapper.props('hotels')).toBe(50);
    expect(wrapper.props('superAdminPercentage')).toBe(20);
    expect(wrapper.props('accommodationPercentages')).toEqual({
      Hotel: 40,
      Apartment: 30,
      Villa: 20,
      Motel: 10,
    });
  });

  it('computes strokeValues correctly', () => {
    const wrapper = mount(Dashboard, {
      props: {
        accommodationPercentages: {
          Hotel: 40,
          Apartment: 30,
          Villa: 20,
          Motel: 10,
        },
      },
      global: {
        config: {
          globalProperties: {
            $page: mockPage,
          },
        },
      },
    });

    const strokeValues = wrapper.vm.strokeValues;
    expect(strokeValues).toHaveLength(4);
    expect(strokeValues[0].dashArray).toBeCloseTo(75.4); // 40% of 188.5
    expect(strokeValues[1].dashArray).toBeCloseTo(56.55); // 30% of 188.5
    expect(strokeValues[2].dashArray).toBeCloseTo(37.7); // 20% of 188.5
    expect(strokeValues[3].dashArray).toBeCloseTo(18.85); // 10% of 188.5
  });

  it('renders key metrics correctly', () => {
    const wrapper = mount(Dashboard, {
      props: {
        order: 200,
        allUsers: 100,
        hotelOwnerCount: 50,
        accommodationPercentages: {  
          Hotel: 40,
          Apartment: 30,
          Villa: 20,
          Motel: 10,
        },
      },
      global: {
        config: {
          globalProperties: {
            $page: mockPage,
          },
        },
      },
    });
  
    const metrics = wrapper.findAll('.text-2xl.font-bold.text-orange-400.ml-10');
    expect(metrics[0].text()).toContain('200');
    expect(metrics[1].text()).toContain('100');
    expect(metrics[2].text()).toContain('50');
  });
  
});
