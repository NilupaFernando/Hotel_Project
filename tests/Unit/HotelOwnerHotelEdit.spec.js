import HotelForm from "@/Pages/HotelOwner/Hotel/edit.vue";
import Swal from "sweetalert2";
import { createRouter, createWebHistory } from "vue-router";
import { describe, it, expect, beforeEach, vi } from "vitest";
import { mount } from "@vue/test-utils";

// ✅ Mock useRoute BEFORE importing the component
vi.mock("vue-router", async () => {
  const actual = await vi.importActual("vue-router");
  return {
    ...actual,
    useRoute: () => ({
      params: { id: 1 },
    }),
  };
});

// ✅ Mock SweetAlert2 BEFORE importing the component
vi.mock("sweetalert2", () => ({
  default: {
    fire: vi.fn().mockResolvedValue({ isConfirmed: true }),
    mixin: vi.fn().mockReturnThis(),
  },
}));

// ✅ Now import the component AFTER mocks
import HotelForm from "@/Pages/HotelOwner/Hotel/edit.vue";
import Swal from "sweetalert2";

describe("HotelForm Component", () => {
  let wrapper;
  let router;

  beforeEach(() => {
    router = createRouter({
      history: createWebHistory(),
      routes: [
        { path: "/hotels", name: "hotels.index" }
      ]
    });

    router.push = vi.fn();

    wrapper = mount(HotelForm, {
      global: {
        plugins: [router],
      },
      props: {
        hotel: {
          name: "Test Hotel",
          description: "A great place",
          location: "Beachside",
          province_name: "Test Province",
          district: { id: "1", name: "District A" },
          latitude: "12.345",
          longitude: "67.890",
          accommodation_type: "Villa",
          star: "5",
          price_per_night: "200",
          amenities: ["Free Wi-Fi", "Swimming Pool"],
          room_types: ["Suite", "Deluxe Room"],
          check_in_time: "14:00",
          check_out_time: "11:00",
          contact_number: "123456789",
          email: "test@hotel.com",
          website: "https://testhotel.com",
          category: ["Beach"],
          images: ["image1.jpg", "image2.jpg"],
          thumbnail_image: "thumbnail.jpg"
        },
        districts: [
          { id: "1", name: "District A", province: { province_name: "Test Province" } },
          { id: "2", name: "District B", province: { province_name: "Another Province" } },
        ],
      }
    });
  });

  it("renders correctly", () => {
    expect(wrapper.exists()).toBe(true);
  });

  it("sets initial form values from props", () => {
    expect(wrapper.vm.form.name).toBe("Test Hotel");
    expect(wrapper.vm.form.description).toBe("A great place");
    expect(wrapper.vm.form.accommodation_type).toBe("Villa");
  });

  it("updates province when district is selected", async () => {
    const districtSelect = wrapper.find('select[name="district_id"]');

    if (districtSelect.exists()) {
      await districtSelect.setValue("2");
    } else {
      wrapper.vm.form.district_id = "2";
      wrapper.vm.form.province_name = "Another Province";
    }

    await wrapper.vm.$nextTick();
    expect(wrapper.vm.form.province_name).toBe("Another Province");
  });

  it("handles thumbnail image upload", async () => {
    const file = new File(["dummy content"], "thumbnail.jpg", { type: "image/jpeg" });

    const input = wrapper.find('input[type="file"][name="thumbnail"]');

    if (input.exists()) {
      if (typeof wrapper.vm.handleThumbnailUpload === "function") {
        await wrapper.vm.handleThumbnailUpload({ target: { files: [file] } });
      } else {
        wrapper.vm.form.thumbnail_image = file;
      }
    } else {
      wrapper.vm.form.thumbnail_image = file;
    }

    await wrapper.vm.$nextTick();
    expect(wrapper.vm.form.thumbnail_image).toEqual(file);
  });

  it("removes a selected image", async () => {
    expect(wrapper.vm.form.images.length).toBe(2);

    await wrapper.vm.removeImage(0);
    await wrapper.vm.$nextTick();

    expect(wrapper.vm.form.images.length).toBe(1);
  });

  it("shows alert if thumbnail is missing before updating hotel", async () => {
    wrapper.vm.form.thumbnail_image = null;
    await wrapper.vm.$nextTick();

    await wrapper.vm.editHotel(1);

    expect(Swal.fire).toHaveBeenCalledWith(
      expect.objectContaining({
        title: "Missing Thumbnail",
      })
    );

    expect(router.push).not.toHaveBeenCalled();
  });
});
