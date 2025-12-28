import { describe, it, expect, vi, beforeEach } from 'vitest';

// Move the mock before using the module
vi.mock('sweetalert2', () => {
  return {
    default: {
      fire: vi.fn(() => Promise.resolve({ isConfirmed: true }))
    }
  };
});

import { shallowMount } from '@vue/test-utils';
import ReservationComponent from '@/Pages/HotelOwner/Dashboard/Dashboard.vue';
import Swal from 'sweetalert2';

describe('ReservationComponent', () => {
  const reservations = [
    { id: 1, status: 'confirmed', user: { name: 'Alice' }, check_in_date: '2025-04-20', check_out_date: '2025-04-23', total_price: 250 },
    { id: 2, status: 'confirmed', user: { name: 'Bob' }, check_in_date: '2025-04-18', check_out_date: '2025-04-21', total_price: 180 },
    { id: 3, status: 'pending', user: { name: 'Charlie' }, reservation_type: 'urgent' },
    { id: 4, status: 'rejected', user: { name: 'Dave' } },
    { id: 5, status: 'cancelled', user: { name: 'Eve' }, check_in_date: '2025-03-15', check_out_date: '2025-03-18', total_price: 300 },
  ];

  let wrapper;

  beforeEach(() => {
    global.window.matchMedia = vi.fn(() => ({
      matches: false,
      addEventListener: vi.fn(),
      removeEventListener: vi.fn(),
    }));

    // Reset the mock before each test
    vi.clearAllMocks();

    wrapper = shallowMount(ReservationComponent, {
      props: {
        reservations,
        reservationRooms: [],
        hotel: [{ id: 1, name: 'Sunset Resort' }],
      },
    });
  });
    
  it('calculates approved reservations correctly', () => {
    expect(wrapper.vm.approvedReservationsCount).toBe(2);
  });

  it('calculates rejected reservations correctly', () => {
    expect(wrapper.vm.rejectedReservationsCount).toBe(1);
  });

  it('calculates pending reservations correctly', () => {
    expect(wrapper.vm.pendingReservationsCount).toBe(1);
  });

  it('filters previous reservations correctly', () => {
    expect(wrapper.vm.previousReservations.length).toBe(1);
  });

  it('filters pending reservations by active tab', async () => {
    wrapper.vm.activeTab = 'urgent';
    await wrapper.vm.$nextTick();
    expect(wrapper.vm.filteredReservations.length).toBe(1);
  });

  it('confirms a reservation via Swal', async () => {
    const request = { user: { name: 'Charlie' } };
    wrapper.vm.confirmReservation(request);

    expect(Swal.fire).toHaveBeenCalledWith(expect.objectContaining({
      title: "Confirm Reservation?",
      text: "Are you sure you want to confirm the reservation for Charlie?",
      icon: "question",
    }));
  });

  it('rejects a reservation via Swal', async () => {
    const request = { user: { name: 'Dave' } };
    wrapper.vm.rejectReservation(request);

    expect(Swal.fire).toHaveBeenCalledWith(expect.objectContaining({
      title: "Reject Reservation?",
      text: "Are you sure you want to reject the reservation for Dave?",
      icon: "warning",
    }));
  });
});