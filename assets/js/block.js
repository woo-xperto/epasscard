// export function blockCreate(dynamicValues) {
//   const wrapper = $("#epasscard_additional_properties");
//   const container = wrapper.find(".fields-container");

//   // let dynamicValues = [];

//   function createFieldBlock(index, initialValue = "") {
//     const fieldBlock = $(`
//       <div class="field-block epasscard-field-group" data-index="${index}">
//         <label>Name</label>
//         <input type="text" placeholder="" value="${initialValue}">

//         <label>Field Type</label>
//         <select>
//           <option value="">Select type</option>
//           <option value="text">Text</option>
//           <option value="email">Email</option>
//           <option value="number">Number</option>
//           <option value="date">Date</option>
//         </select>

//         <div class="checkbox-group">
//           <input type="checkbox" id="required-${index}" checked>
//           <label for="required-${index}">Is This Field Required?</label>
//         </div>

//         <button type="button" class="remove-btn epasscard-remove-btn">Remove</button>
//       </div>
//     `);

//     // Update dynamicValues on keyup
//     fieldBlock.find('input[type="text"]').on("keyup", function () {
//       dynamicValues[index] = `{${$(this).val()}}`;
//     });

//     return fieldBlock;
//   }

//   function rebuildBlocks() {
//     container.empty();
//     dynamicValues.forEach((value, newIndex) => {
//       const cleanValue = value.replace(/[{}]/g, "");
//       const block = createFieldBlock(newIndex, cleanValue);
//       container.append(block);
//     });

//     // Update attribute
//     $(".wrap.epasscard-admin").attr("attributeName", dynamicValues);
//   }

//   wrapper.find(".add-field-btn").on("click", function () {
//     dynamicValues.push("{}");
//     rebuildBlocks();
//   });

//   container.on("click", ".remove-btn", function () {
//     const index = parseInt($(this).closest(".field-block").attr("data-index"));

//     if (!isNaN(index)) {
//       dynamicValues.splice(index, 1);
//       rebuildBlocks();
//     }
//   });
// }
